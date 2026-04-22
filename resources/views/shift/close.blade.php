@extends('layouts.app')
@section('title', 'Tutup Shift')
@section('page_title', 'Tutup Shift Kasir')
@section('page_subtitle', 'Rekap penjualan dan hitung kas')

@section('content')
<div style="max-width: 600px;">
    <div class="glass-card" style="margin-bottom: 1.5rem;">
        <h3 style="margin-bottom: 1rem;">📊 Ringkasan Shift</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div><p style="color: var(--text-muted); font-size: 0.8rem;">Modal Awal</p><p style="font-weight: 700;">Rp {{ number_format($shift->opening_cash, 0, ',', '.') }}</p></div>
            <div><p style="color: var(--text-muted); font-size: 0.8rem;">Total Penjualan</p><p style="font-weight: 700;">Rp {{ number_format($totalSales, 0, ',', '.') }}</p></div>
            <div><p style="color: var(--text-muted); font-size: 0.8rem;">Jumlah Transaksi</p><p style="font-weight: 700;">{{ $totalTransactions }}</p></div>
            <div>
                <p style="color: var(--text-muted); font-size: 0.8rem;">Kas Diharapkan</p>
                <p style="font-weight: 700; color: var(--accent);">Rp {{ number_format($expectedCash, 0, ',', '.') }}</p>
                <p style="color: var(--text-muted); font-size: 0.65rem; margin-top: 0.25rem;">(Modal Awal + Penjualan Tunai - Kembalian)</p>
            </div>
        </div>
    </div>
    <div class="glass-card">
        <form method="POST" action="/shift/close">
            @csrf
            <div class="form-group">
                <label class="form-label">Kas Aktual (hitung fisik uang di laci)</label>
                <input type="number" name="closing_cash" class="form-input" placeholder="Masukkan jumlah kas aktual" required style="font-size: 1.25rem;">
                <p style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.5rem;">
                    * Hitunglah semua uang tunai fisik yang ada di dalam laci kasir saat ini secara manual.
                </p>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan (opsional)</label>
                <textarea name="notes" class="form-input" rows="3" placeholder="Catatan tambahan..."></textarea>
            </div>
            <button type="submit" class="btn btn-danger" style="width: 100%;">
                <i data-lucide="lock" style="width: 18px;"></i> Tutup Shift
            </button>
        </form>
    </div>
</div>
@endsection
