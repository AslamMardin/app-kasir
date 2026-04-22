@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('page_title', 'Detail Transaksi')
@section('page_subtitle', $transaction->invoice_number)

@section('content')
<a href="/transactions" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;"><i data-lucide="arrow-left" style="width:16px;"></i> Kembali</a>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <div>
        <div class="glass-card" style="margin-bottom: 1.5rem;">
            <h3 style="margin-bottom: 1rem;">Item Transaksi</h3>
            <table class="data-table">
                <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td style="font-weight: 600;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($transaction->status === 'completed')
        <div class="glass-card">
            <h3 style="margin-bottom: 1rem; color: var(--danger);">Void Transaksi</h3>
            <form method="POST" action="/transactions/{{ $transaction->id }}/void" onsubmit="return confirm('Yakin void transaksi ini?')">
                @csrf
                <div class="form-group"><label class="form-label">Alasan Void</label><textarea name="void_reason" class="form-input" rows="3" required placeholder="Masukkan alasan void..."></textarea></div>
                <button type="submit" class="btn btn-danger">Void Transaksi</button>
            </form>
        </div>
        @endif
    </div>
    <div>
        <div class="glass-card">
            <h3 style="margin-bottom: 1rem;">Info Transaksi</h3>
            <div style="font-size: 0.875rem;">
                <p style="margin-bottom: 0.75rem;"><span style="color: var(--text-muted);">Status:</span><br>
                    @if($transaction->status === 'completed')<span style="color: var(--accent); font-weight: 600;">✅ Selesai</span>
                    @else<span style="color: var(--danger); font-weight: 600;">❌ Void</span>@endif
                </p>
                <p style="margin-bottom: 0.75rem;"><span style="color: var(--text-muted);">Kasir:</span><br>{{ $transaction->user->name }}</p>
                <p style="margin-bottom: 0.75rem;"><span style="color: var(--text-muted);">Tanggal:</span><br>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                <p style="margin-bottom: 0.75rem;"><span style="color: var(--text-muted);">Member:</span><br>{{ $transaction->member->name ?? 'Non-member' }}</p>
                <hr style="border-color: var(--border); margin: 1rem 0;">
                <p style="margin-bottom: 0.5rem;"><span style="color: var(--text-muted);">Subtotal:</span> Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</p>
                <p style="margin-bottom: 0.5rem;"><span style="color: var(--text-muted);">Diskon:</span> Rp {{ number_format($transaction->discount_total, 0, ',', '.') }}</p>
                <p style="font-size: 1.25rem; font-weight: 700; color: var(--accent); margin-top: 0.5rem;">Total: Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
                <hr style="border-color: var(--border); margin: 1rem 0;">
                <p style="margin-bottom: 0.5rem;"><span style="color: var(--text-muted);">Metode:</span> {{ strtoupper($transaction->payment_method) }}</p>
                <p style="margin-bottom: 0.5rem;"><span style="color: var(--text-muted);">Dibayar:</span> Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</p>
                <p><span style="color: var(--text-muted);">Kembalian:</span> Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
