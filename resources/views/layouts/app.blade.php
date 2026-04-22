<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Toko Campalagian</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('css')
</head>

<body>
    <div class="sidebar">
        <div style="padding: 1rem 0; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
            <div style="background: var(--accent); padding: 0.5rem; border-radius: 8px;">
                <i data-lucide="shopping-cart" style="color: white;"></i>
            </div>
            <h2 style="font-size: 1.25rem; font-weight: 700;">Campalagian</h2>
        </div>

        <nav>
            <a href="/dashboard" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
            <a href="/pos" class="nav-link {{ request()->is('pos*') ? 'active' : '' }}">
                <i data-lucide="monitor" style="margin-right:2px"></i> Kasir (POS)
            </a>
            <a href="/products" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                <i data-lucide="package" style="margin-right:2px"></i> Produk
            </a>
            <a href="/categories" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
                <i data-lucide="tag" style="margin-right:2px"></i> Kategori
            </a>
            <a href="/members" class="nav-link {{ request()->is('members*') ? 'active' : '' }}">
                <i data-lucide="users" style="margin-right:2px"></i> Member
            </a>
            <a href="/transactions" class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}">
                <i data-lucide="history" style="margin-right:2px"></i> Transaksi
            </a>
            <a href="/reports/daily" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                <i data-lucide="bar-chart-3" style="margin-right:2px"></i> Laporan
            </a>
        </nav>

        <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div
                    style="background: #334155; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="user" style="width: 20px; color: var(--text-muted);"></i>
                </div>
                <div>
                    <p style="font-size: 0.875rem; font-weight: 600;">{{ Auth::user()->name }}</p>
                    <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: capitalize;">
                        {{ Auth::user()->role }}</p>
                </div>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="nav-link"
                    style="width: 100%; border: none; background: transparent; cursor: pointer;">
                    <i data-lucide="log-out"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <main class="main-content">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 700;">@yield('page_title')</h1>
                <p style="color: var(--text-muted); font-size: 0.875rem;">@yield('page_subtitle', 'Selamat datang kembali!')</p>
            </div>
            <div id="current-time" style="font-size: 0.875rem; color: var(--text-muted); font-weight: 500;">
                <!-- Realtime time via JS -->
            </div>
        </header>

        @yield('content')
    </main>

    <script>
        lucide.createIcons();

        // Realtime Clock
        function updateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('current-time').textContent = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>
    @stack('js')
</body>

</html>
