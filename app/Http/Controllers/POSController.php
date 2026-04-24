<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Shift;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class POSController extends Controller
{
    public function index()
    {
        $shift = Shift::where('user_id', Auth::id())
            ->whereNull('closed_at')
            ->first();

        if (!$shift) {
            return redirect('/shift/open')->with('warning', 'Silakan buka shift terlebih dahulu.');
        }

        $categories = \App\Models\Category::all();

        return view('pos.index', compact('shift', 'categories'));
    }

    public function searchProduct(Request $request)
    {
        $query = $request->input('q', '');

        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('barcode', $query)
                  ->orWhere('name', 'like', "%{$query}%");
            })
            ->take(20)
            ->get(['id', 'barcode', 'name', 'selling_price', 'stock', 'unit']);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,qris,edc',
            'payment_amount' => 'required|numeric|min:0',
            'member_phone' => 'nullable|string',
        ]);

        $shift = Shift::where('user_id', Auth::id())
            ->whereNull('closed_at')
            ->first();

        if (!$shift) {
            return response()->json(['error' => 'Shift belum dibuka.'], 422);
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $itemsData = [];

            // Load all products at once to avoid N+1 inside loop
            $productIds = collect($request->items)->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($request->items as $item) {
                $product = $products->get($item['product_id']);

                if (!$product) {
                    throw new \Exception("Produk dengan ID {$item['product_id']} tidak ditemukan.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi.");
                }

                $itemSubtotal = $product->selling_price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->selling_price,
                    'quantity' => $item['quantity'],
                    'discount' => 0,
                    'subtotal' => $itemSubtotal,
                ];

                // Reduce stock
                $product->decrement('stock', $item['quantity']);
            }

            $grandTotal = $subtotal;
            $changeAmount = max(0, $request->payment_amount - $grandTotal);

            // Find or create member
            $memberId = null;
            if ($request->member_phone) {
                $member = Member::where('phone', $request->member_phone)->first();
                if ($member) {
                    $memberId = $member->id;
                    $member->increment('total_spending', $grandTotal);
                    $member->increment('points', floor($grandTotal / 1000));
                }
            }

            // Generate invoice number
            $todayCount = Transaction::whereDate('created_at', Carbon::today())->count() + 1;
            $invoiceNumber = 'INV-' . Carbon::today()->format('Ymd') . '-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'shift_id' => $shift->id,
                'user_id' => Auth::id(),
                'member_id' => $memberId,
                'subtotal' => $subtotal,
                'discount_total' => 0,
                'tax_total' => 0,
                'grand_total' => $grandTotal,
                'payment_method' => $request->payment_method,
                'payment_amount' => $request->payment_amount,
                'change_amount' => $changeAmount,
                'status' => 'completed',
            ]);

            foreach ($itemsData as $itemData) {
                $transaction->items()->create($itemData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction' => $transaction->load('items'),
                'invoice_number' => $invoiceNumber,
                'change_amount' => $changeAmount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load('items', 'user', 'member');
        return view('pos.receipt', compact('transaction'));
    }
}
