@extends('layouts.app')
@section('title', 'Buka Shift')
@section('page_title', 'Buka Shift Kasir')
@section('page_subtitle', 'Masukkan modal awal untuk memulai shift')

@section('content')
<div style="max-width: 500px;">
    <div class="glass-card">
        @if(session('warning'))
            <div style="background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3); color: #fcd34d; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem;">{{ session('warning') }}</div>
        @endif
        <form method="POST" action="/shift/open">
            @csrf
            <div class="form-group">
                <label class="form-label">Modal Awal (Rp)</label>
                <input type="number" name="opening_cash" class="form-input" placeholder="Contoh: 500000" required autofocus style="font-size: 1.25rem;">
                <p style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.5rem;">
                    * Masukkan jumlah uang tunai yang ada di laci saat ini sebagai modal untuk uang kembalian pelanggan.
                </p>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i data-lucide="play" style="width: 18px;"></i> Mulai Shift
            </button>
        </form>
    </div>
</div>
@endsection
