<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Eventure</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-1: #e8f4fd;
            --bg-2: #bfdfff;
            --surface: #ffffff;
            --surface-soft: #f8fafb;
            --line: rgba(27, 108, 168, 0.25);
            --text: #0a2342;
            --muted: #5b7a96;
            --accent: #1b6ca8;
            --accent-strong: #0a2342;
            --cta: #ff6b35;
            --scrollbar-thumb: rgba(107, 114, 128, 0.45);
            --scrollbar-thumb-hover: rgba(107, 114, 128, 0.68);
            --scrollbar-track: transparent;
        }

        * { box-sizing: border-box; }

        * {
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);
        }

        *::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        *::-webkit-scrollbar-track {
            background: var(--scrollbar-track);
        }

        *::-webkit-scrollbar-thumb {
            background-color: var(--scrollbar-thumb);
            border-radius: 999px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        *::-webkit-scrollbar-thumb:hover {
            background-color: var(--scrollbar-thumb-hover);
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            margin: 0;
            height: 100dvh;
            font-family: 'Sora', sans-serif;
            color: var(--text);
            background:
                radial-gradient(900px 500px at 12% 8%, rgba(88, 164, 207, 0.22), transparent 60%),
                radial-gradient(800px 450px at 88% 16%, rgba(27, 108, 168, 0.18), transparent 62%),
                linear-gradient(165deg, var(--bg-1), var(--bg-2));
            position: relative;
        }

        .orb {
            position: fixed;
            border-radius: 9999px;
            pointer-events: none;
            filter: blur(2px);
            opacity: 0.45;
        }

        .orb-1 {
            width: 260px;
            height: 260px;
            top: 24%;
            left: -90px;
            background: radial-gradient(circle, rgba(88, 164, 207, 0.24) 0%, rgba(88, 164, 207, 0) 70%);
        }

        .orb-2 {
            width: 320px;
            height: 320px;
            right: -120px;
            bottom: 10%;
            background: radial-gradient(circle, rgba(27, 108, 168, 0.26) 0%, rgba(27, 108, 168, 0) 72%);
        }

        .top-nav {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 28px;
            background: rgba(232, 244, 253, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--line);
        }

        .brand {
            text-decoration: none;
            font-weight: 800;
            letter-spacing: -0.03em;
            font-size: 1.7rem;
        }

        .brand-event { color: #0a2342; }
        .brand-flow { color: var(--accent); }

        .page-wrap {
            height: calc(100dvh - 74px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .login-card {
            width: 100%;
            max-width: 480px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 16px 30px rgba(10, 35, 66, 0.12);
        }

        .title {
            margin: 0;
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .subtitle {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 0.94rem;
        }

        .back-link {
            color: var(--accent);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: color 180ms ease, opacity 180ms ease;
        }

        .back-link:hover {
            color: var(--cta);
            opacity: 1;
        }

        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }

        .field { margin-bottom: 14px; }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1b4164;
        }

        input[type='email'],
        input[type='password'] {
            width: 100%;
            border: 1px solid rgba(88, 164, 207, 0.35);
            border-radius: 10px;
            background: #ffffff;
            color: #0a2342;
            padding: 12px 13px;
            font: inherit;
            outline: none;
            transition: border-color 180ms ease, box-shadow 180ms ease, background 180ms ease;
        }

        input::placeholder {
            color: #7a95ad;
        }

        input[type='email']:focus,
        input[type='password']:focus {
            border-color: rgba(27, 108, 168, 0.8);
            box-shadow: 0 0 0 3px rgba(88, 164, 207, 0.22);
            background: #f8fcff;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 4px 0 16px;
        }

        .remember-row input {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
            margin: 0;
        }

        .remember-row label {
            margin: 0;
            color: var(--muted);
            font-weight: 500;
            font-size: 0.88rem;
        }

        .btn-primary {
            width: 100%;
            border: 0;
            border-radius: 11px;
            background: var(--accent);
            color: #ffffff;
            padding: 12px 16px;
            font: inherit;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: transform 180ms ease, filter 180ms ease, box-shadow 180ms ease;
            box-shadow: 0 10px 24px rgba(27, 108, 168, 0.22);
        }

        .btn-primary:hover {
            background: var(--accent-strong);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .divider {
            margin: 20px 0 14px;
            border: 0;
            border-top: 1px solid rgba(88, 164, 207, 0.35);
        }

        .quick-title {
            margin: 0 0 10px;
            color: var(--muted);
            font-size: 0.86rem;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-ghost {
            border: 1px solid rgba(88, 164, 207, 0.42);
            background: #f4faff;
            color: #1b6ca8;
            border-radius: 10px;
            padding: 10px 12px;
            font: inherit;
            font-size: 0.86rem;
            font-weight: 600;
            cursor: pointer;
            transition: border-color 180ms ease, background 180ms ease, color 180ms ease;
        }

        .btn-ghost:hover {
            border-color: rgba(27, 108, 168, 0.52);
            background: rgba(88, 164, 207, 0.15);
            color: #0a2342;
        }

        .alert-success,
        .alert-error {
            border-radius: 11px;
            padding: 11px 13px;
            margin-bottom: 14px;
            font-size: 0.88rem;
        }

        .alert-success {
            background: rgba(88, 164, 207, 0.18);
            border: 1px solid rgba(27, 108, 168, 0.36);
            color: #0a2342;
        }

        .alert-error {
            background: rgba(127, 29, 29, 0.28);
            border: 1px solid rgba(248, 113, 113, 0.34);
            color: #fee2e2;
        }

        .alert-error ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        @media (max-width: 560px) {
            .top-nav {
                padding: 14px 16px;
            }

            .brand {
                font-size: 1.45rem;
            }

            .login-card {
                padding: 22px;
                border-radius: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="orb orb-1" aria-hidden="true"></div>
    <div class="orb orb-2" aria-hidden="true"></div>

    <nav class="top-nav">
        <a href="{{ route('landing', [], false) }}" class="brand" aria-label="Eventure home">
            <span class="brand-event">Even</span><span class="brand-flow">ture</span>
        </a>
    </nav>

    <main class="page-wrap">
        <section class="login-card" aria-label="Sign in card">
            @if (session('status'))
                <div class="alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <strong>Please fix the following:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="header-row">
                <div>
                    <h1 class="title">Sign In</h1>
                    <p class="subtitle">Access your Eventure dashboard</p>
                </div>
                <a class="back-link" href="{{ route('landing', [], false) }}">Back</a>
            </div>

            <form action="{{ route('login', [], false) }}" method="POST">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter your password" required>
                </div>

                <div class="remember-row">
                    <input id="remember" name="remember" type="checkbox" value="1">
                    <label for="remember">Remember me</label>
                </div>

                <button class="btn-primary" type="submit">Sign In</button>
            </form>

            @if (app()->environment(['local', 'testing']))
                <hr class="divider">
                <p class="quick-title"><strong>Quick Test Login</strong></p>
                <div class="actions">
                    <a class="btn-ghost" href="{{ route('login.test-as.quick', ['role' => 'event_staff'], false) }}">Event Staff</a>
                    <a class="btn-ghost" href="{{ route('login.test-as.quick', ['role' => 'admin'], false) }}">Admin</a>
                </div>
            @endif
        </section>
    </main>
</body>
</html>
