<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function open()
    {
        $activeShift = Shift::where('user_id', Auth::id())
            ->whereNull('closed_at')
            ->first();

        if ($activeShift) {
            return redirect('/pos')->with('info', 'Anda sudah memiliki shift aktif.');
        }

        return view('shift.open');
    }

    public function storeOpen(Request $request)
    {
        $request->validate([
            'opening_cash' => 'required|numeric|min:0',
        ]);

        Shift::create([
            'user_id' => Auth::id(),
            'opened_at' => Carbon::now(),
            'opening_cash' => $request->opening_cash,
        ]);

        return redirect('/pos')->with('success', 'Shift berhasil dibuka!');
    }

    public function close()
    {
        $shift = Shift::where('user_id', Auth::id())
            ->whereNull('closed_at')
            ->first();

        if (! $shift) {
            return redirect('/dashboard')->with('warning', 'Tidak ada shift aktif.');
        }

        // Calculate expected cash - Optimized to single query
        $stats = Transaction::where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->selectRaw("
                SUM(CASE WHEN payment_method = 'cash' THEN grand_total ELSE 0 END) as cash_sales,
                SUM(CASE WHEN payment_method = 'cash' THEN change_amount ELSE 0 END) as cash_change,
                SUM(grand_total) as total_sales,
                COUNT(*) as total_transactions
            ")
            ->first();

        $expectedCash = $shift->opening_cash + ($stats->cash_sales ?? 0) - ($stats->cash_change ?? 0);
        $totalSales = $stats->total_sales ?? 0;
        $totalTransactions = $stats->total_transactions ?? 0;

        return view('shift.close', compact('shift', 'expectedCash', 'totalSales', 'totalTransactions'));
    }

    public function storeClose(Request $request)
    {
        $request->validate([
            'closing_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $shift = Shift::where('user_id', Auth::id())
            ->whereNull('closed_at')
            ->first();

        if (! $shift) {
            return redirect('/dashboard')->with('warning', 'Tidak ada shift aktif.');
        }

        $stats = Transaction::where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->selectRaw("
                SUM(CASE WHEN payment_method = 'cash' THEN grand_total ELSE 0 END) as cash_sales,
                SUM(CASE WHEN payment_method = 'cash' THEN change_amount ELSE 0 END) as cash_change
            ")
            ->first();

        $expectedCash = $shift->opening_cash + ($stats->cash_sales ?? 0) - ($stats->cash_change ?? 0);

        $shift->update([
            'closed_at' => Carbon::now(),
            'closing_cash' => $request->closing_cash,
            'expected_cash' => $expectedCash,
            'cash_difference' => $request->closing_cash - $expectedCash,
            'notes' => $request->notes,
        ]);

        return redirect('/dashboard')->with('success', 'Shift berhasil ditutup.');
    }
}
