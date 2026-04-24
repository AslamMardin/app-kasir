@extends('layouts.app')
@section('title', 'Laporan Bulanan')
@section('page_title', 'Laporan Penjualan Bulanan')
@section('page_subtitle', 'Rekapitulasi penjualan per hari dalam satu bulan')

@section('content')
<div class="glass-card animate-fade" style="margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Bulan</label>
            <select name="month" class="form-input" style="color: var(--accent); font-weight: bold;">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ sprintf('%02d', $m) }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Tahun</label>
            <select name="year" class="form-input" style="color: var(--accent); font-weight: bold;">
                @for($y=date('Y'); $y>=date('Y')-5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="/reports/monthly/pdf?month={{ $month }}&year={{ $year }}" class="btn btn-primary" style="background: #ef4444;">
            <i data-lucide="file-text" style="width: 16px;"></i> Download PDF
        </a>
    </form>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="glass-card">
        <p style="color: var(--text-muted); font-size: 0.875rem;">Total Penjualan</p>
        <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
    </div>
    <div class="glass-card">
        <p style="color: var(--text-muted); font-size: 0.875rem;">Total Transaksi</p>
        <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $totalTransactions }}</p>
    </div>
</div>

<div class="glass-card" style="padding: 0; overflow: hidden;">
    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jumlah Transaksi</th>
                <th>Total Penjualan</th>
                <th>Rata-rata / Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $row)
            <tr>
                <td>{{ date('d/m/Y', strtotime($row->date)) }}</td>
                <td>{{ $row->total_transactions }}</td>
                <td style="font-weight: 600;">Rp {{ number_format($row->total_sales, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($row->total_sales / $row->total_transactions, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2rem;">Tidak ada data penjualan untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
