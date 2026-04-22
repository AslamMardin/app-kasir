@extends('layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan penjualan hari ini')

@section('content')
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="glass-card animate-fade">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Penjualan Hari Ini</p>
                <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">Rp {{ number_format($salesToday, 0, ',', '.') }}</p>
            </div>
            <div style="background: rgba(16, 185, 129, 0.1); padding: 0.75rem; border-radius: 12px;">
                <i data-lucide="trending-up" style="color: var(--accent);"></i>
            </div>
        </div>
    </div>
    <div class="glass-card animate-fade" style="animation-delay: 0.1s;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Total Transaksi</p>
                <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $transactionsToday }}</p>
            </div>
            <div style="background: rgba(99, 102, 241, 0.1); padding: 0.75rem; border-radius: 12px;">
                <i data-lucide="receipt" style="color: #818cf8;"></i>
            </div>
        </div>
    </div>
    <div class="glass-card animate-fade" style="animation-delay: 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Total Produk</p>
                <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $totalProducts }}</p>
            </div>
            <div style="background: rgba(245, 158, 11, 0.1); padding: 0.75rem; border-radius: 12px;">
                <i data-lucide="package" style="color: #f59e0b;"></i>
            </div>
        </div>
    </div>
    <div class="glass-card animate-fade" style="animation-delay: 0.3s;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Total Member</p>
                <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.25rem;">{{ $totalMembers }}</p>
            </div>
            <div style="background: rgba(236, 72, 153, 0.1); padding: 0.75rem; border-radius: 12px;">
                <i data-lucide="users" style="color: #ec4899;"></i>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    {{-- Sales Chart --}}
    <div class="glass-card">
        <h3 style="margin-bottom: 1rem; font-size: 1rem;">Penjualan 7 Hari Terakhir</h3>
        <div style="display: flex; align-items: flex-end; gap: 0.5rem; height: 200px; padding-top: 1rem;">
            @php $maxSale = max(array_column($salesChart, 'total')) ?: 1; @endphp
            @foreach($salesChart as $day)
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                    <div style="width: 100%; background: linear-gradient(to top, var(--accent), rgba(16,185,129,0.3)); border-radius: 6px 6px 0 0; height: {{ max(4, ($day['total'] / $maxSale) * 180) }}px; transition: height 0.5s;"></div>
                    <span style="font-size: 0.7rem; color: var(--text-muted);">{{ $day['date'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Low Stock --}}
    <div class="glass-card">
        <h3 style="margin-bottom: 1rem; font-size: 1rem;">⚠️ Stok Menipis</h3>
        @forelse($lowStockProducts as $product)
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--border);">
                <span style="font-size: 0.875rem;">{{ $product->name }}</span>
                <span style="color: var(--danger); font-weight: 600; font-size: 0.875rem;">{{ $product->stock }} {{ $product->unit }}</span>
            </div>
        @empty
            <p style="color: var(--text-muted); font-size: 0.875rem;">Semua stok aman 👍</p>
        @endforelse
    </div>
</div>
@endsection
