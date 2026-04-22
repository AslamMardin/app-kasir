<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        $transactions = Transaction::with('items', 'user')
            ->where('status', 'completed')
            ->whereDate('created_at', $date)
            ->latest()->get();
        $totalSales = $transactions->sum('grand_total');
        $totalTransactions = $transactions->count();
        return view('reports.daily', compact('transactions', 'totalSales', 'totalTransactions', 'date'));
    }

    public function downloadPdf(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        $transactions = Transaction::with('items', 'user')
            ->where('status', 'completed')
            ->whereDate('created_at', $date)
            ->latest()->get();
        $totalSales = $transactions->sum('grand_total');
        $totalTransactions = $transactions->count();

        $pdf = Pdf::loadView('reports.pdf', compact('transactions', 'totalSales', 'totalTransactions', 'date'));
        
        return $pdf->download('laporan-penjualan-'.$date->format('Y-m-d').'.pdf');
    }
}
