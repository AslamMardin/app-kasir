@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')

@section('content')
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 0.5rem;">
        <input type="text" name="search" class="form-input" style="width: 250px;" placeholder="Cari invoice..." value="{{ request('search') }}">
        <select name="status" class="form-input" style="width: 150px;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="voided" {{ request('status') == 'voided' ? 'selected' : '' }}>Voided</option>
        </select>
        <button class="btn btn-primary">Cari</button>
    </form>
</div>
@if(session('success'))
    <div style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem;">{{ session('success') }}</div>
@endif
<div class="glass-card" style="padding: 0; overflow: hidden;">
    <table class="data-table">
        <thead><tr><th>Invoice</th><th>Tanggal</th><th>Kasir</th><th>Member</th><th>Total</th><th>Metode</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td style="font-family: monospace; font-size: 0.8rem;">{{ $t->invoice_number }}</td>
                <td style="font-size: 0.8rem;">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $t->user->name ?? '-' }}</td>
                <td>{{ $t->member->name ?? '-' }}</td>
                <td style="font-weight: 600;">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                <td><span style="text-transform: uppercase; font-size: 0.75rem; background: rgba(255,255,255,0.05); padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $t->payment_method }}</span></td>
                <td>
                    @if($t->status === 'completed')
                        <span style="color: var(--accent);">✅ Selesai</span>
                    @else
                        <span style="color: var(--danger);">❌ Void</span>
                    @endif
                </td>
                <td><a href="/transactions/{{ $t->id }}" style="color: var(--accent); text-decoration: none;">Detail</a></td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align: center; color: var(--text-muted);">Belum ada transaksi</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top: 1rem;">{{ $transactions->links() }}</div>
@endsection
