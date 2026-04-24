<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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
        
        return $pdf->download('laporan-harian-'.$date->format('Y-m-d').'.pdf');
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?: date('m');
        $year = $request->year ?: date('Y');

        $transactions = Transaction::where('status', 'completed')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(grand_total) as total_sales')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $totalSales = $transactions->sum('total_sales');
        $totalTransactions = $transactions->sum('total_transactions');

        return view('reports.monthly', compact('transactions', 'totalSales', 'totalTransactions', 'month', 'year'));
    }

    public function downloadMonthlyPdf(Request $request)
    {
        $month = $request->month ?: date('m');
        $year = $request->year ?: date('Y');

        $transactions = Transaction::where('status', 'completed')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(grand_total) as total_sales')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $totalSales = $transactions->sum('total_sales');
        $totalTransactions = $transactions->sum('total_transactions');

        $pdf = Pdf::loadView('reports.monthly_pdf', compact('transactions', 'totalSales', 'totalTransactions', 'month', 'year'));
        
        return $pdf->download("laporan-bulanan-{$year}-{$month}.pdf");
    }
}
