<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $salesToday = Transaction::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->sum('grand_total');

        $transactionsToday = Transaction::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->count();

        $totalProducts = Product::where('is_active', true)->count();
        $totalMembers = Member::count();

        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->take(5)
            ->get();

        // Sales last 7 days
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesChart[] = [
                'date' => $date->format('d/m'),
                'total' => Transaction::where('status', 'completed')
                    ->whereDate('created_at', $date)
                    ->sum('grand_total'),
            ];
        }

        // Top selling products today
        $topProducts = DB::table('transaction_items')
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->where('transactions.status', 'completed')
            ->whereDate('transactions.created_at', $today)
            ->select('transaction_items.product_name', DB::raw('SUM(transaction_items.quantity) as total_qty'), DB::raw('SUM(transaction_items.subtotal) as total_sales'))
            ->groupBy('transaction_items.product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'salesToday',
            'transactionsToday',
            'totalProducts',
            'totalMembers',
            'lowStockProducts',
            'salesChart',
            'topProducts'
        ));
    }
}
