<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Toko Campalagian</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            position: relative;
            overflow: hidden;
        }
        .login-wrapper::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
        }
        .login-wrapper::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
            bottom: -100px;
            left: -100px;
            border-radius: 50%;
        }
        .login-box {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.6s ease forwards;
            position: relative;
            z-index: 1;
        }
        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }
        .login-logo-icon {
            background: linear-gradient(135deg, var(--accent), #059669);
            padding: 0.75rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .login-subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }
        .error-msg {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .login-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--accent), #059669);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-logo">
                <div class="login-logo-icon">
                    <i data-lucide="shopping-cart" style="color: white; width: 28px; height: 28px;"></i>
                </div>
                <span class="login-title">Toko Campalagian</span>
            </div>
            <p class="login-subtitle">Masuk ke sistem kasir</p>

            @if ($errors->any())
                <div class="error-msg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input" placeholder="Masukkan username" value="{{ old('username') }}" autofocus required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="login-btn">Masuk</button>
            </form>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
