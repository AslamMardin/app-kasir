@extends('layouts.app')
@section('title', 'Laporan Harian')
@section('page_title', 'Laporan Penjualan Harian')

@section('content')
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; align-items: center;">
    <form method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
        <input type="date" name="date" class="form-input" style="width: 200px;" value="{{ $date->format('Y-m-d') }}">
        <button class="btn btn-primary">Lihat</button>
        <a href="{{ url('/reports/daily/pdf?date=' . $date->format('Y-m-d')) }}" class="btn btn-primary" style="background: #ef4444;">
            <i data-lucide="file-text" style="width:16px;"></i> Download PDF
        </a>
    </form>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="glass-card">
        <p style="color: var(--text-muted); font-size: 0.875rem;">Total Penjualan</p>
        <p style="font-size: 1.5rem; font-weight: 700; color: var(--accent);">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
    </div>
    <div class="glass-card">
        <p style="color: var(--text-muted); font-size: 0.875rem;">Jumlah Transaksi</p>
        <p style="font-size: 1.5rem; font-weight: 700;">{{ $totalTransactions }}</p>
    </div>
</div>

<div class="glass-card" style="padding: 0; overflow: hidden;">
    <table class="data-table">
        <thead><tr><th>Invoice</th><th>Waktu</th><th>Kasir</th><th>Items</th><th>Total</th><th>Metode</th></tr></thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td style="font-family: monospace; font-size: 0.8rem;">{{ $t->invoice_number }}</td>
                <td>{{ $t->created_at->format('H:i:s') }}</td>
                <td>{{ $t->user->name ?? '-' }}</td>
                <td>{{ $t->items->count() }} item</td>
                <td style="font-weight: 600;">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                <td style="text-transform: uppercase; font-size: 0.8rem;">{{ $t->payment_method }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center; color: var(--text-muted);">Tidak ada transaksi pada tanggal ini</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
