<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user', 'member')->latest();
        if ($request->search) {
            $query->where('invoice_number', 'like', "%{$request->search}%");
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $transactions = $query->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('items', 'user', 'member', 'shift');
        return view('transactions.show', compact('transaction'));
    }

    public function void(Request $request, Transaction $transaction)
    {
        $request->validate(['void_reason' => 'required|string|min:5']);
        if ($transaction->status === 'voided') {
            return back()->with('error', 'Transaksi sudah di-void.');
        }
        foreach ($transaction->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }
        $transaction->update([
            'status' => 'voided',
            'voided_by' => Auth::id(),
            'void_reason' => $request->void_reason,
        ]);
        return back()->with('success', 'Transaksi berhasil di-void.');
    }
}
