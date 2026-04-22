@extends('layouts.app')
@section('title', 'Member')
@section('page_title', 'Manajemen Member')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 0.5rem;">
        <input type="text" name="search" class="form-input" style="width: 300px;" placeholder="Cari member..." value="{{ request('search') }}">
        <button class="btn btn-primary">Cari</button>
    </form>
    <a href="/members/create" class="btn btn-primary"><i data-lucide="plus" style="width:16px;"></i> Tambah Member</a>
</div>
@if(session('success'))
    <div style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem;">{{ session('success') }}</div>
@endif
<div class="glass-card" style="padding: 0; overflow: hidden;">
    <table class="data-table">
        <thead><tr><th>No. HP</th><th>Nama</th><th>Poin</th><th>Total Belanja</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($members as $m)
            <tr>
                <td style="font-family: monospace;">{{ $m->phone }}</td>
                <td>{{ $m->name }}</td>
                <td style="color: var(--accent); font-weight: 600;">{{ $m->points }}</td>
                <td>Rp {{ number_format($m->total_spending, 0, ',', '.') }}</td>
                <td>
                    <a href="/members/{{ $m->id }}/edit" style="color: var(--accent); text-decoration: none; margin-right: 0.5rem;">Edit</a>
                    <form action="/members/{{ $m->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus member?')">@csrf @method('DELETE')<button style="color: var(--danger); background:none; border:none; cursor:pointer;">Hapus</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">Belum ada member</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top: 1rem;">{{ $members->links() }}</div>
@endsection
