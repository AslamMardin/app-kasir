<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        if (!$shift) {
            return redirect('/dashboard')->with('warning', 'Tidak ada shift aktif.');
        }

        // Calculate expected cash
        $cashSales = Transaction::where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('grand_total');

        $cashChange = Transaction::where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('change_amount');

        $expectedCash = $shift->opening_cash + $cashSales - $cashChange;

        $totalSales = Transaction::where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->sum('grand_total');

        $totalTransactions = Transaction::where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->count();

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

        if (!$shift) {
            return redirect('/dashboard')->with('warning', 'Tidak ada shift aktif.');
        }

        $cashSales = Transaction::where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('grand_total');

        $cashChange = Transaction::where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('change_amount');

        $expectedCash = $shift->opening_cash + $cashSales - $cashChange;

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
