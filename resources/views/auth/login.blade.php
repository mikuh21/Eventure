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
            box-shadow: 0 16px 30px rgba(10, 35, 66, 0.12);            position: relative;
            overflow: visible;        }

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
        input[type='password'],
        input[type='text'] {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid rgba(88, 164, 207, 0.35);
            border-radius: 10px;
            background: #ffffff;
            color: #0a2342;
            padding: 12px 13px;
            padding-right: 58px;
            font: inherit;
            outline: none;
            transition: border-color 180ms ease, box-shadow 180ms ease, background 180ms ease;
        }

        input::placeholder {
            color: #7a95ad;
        }

        input[type='email']:focus,
        input[type='password']:focus,
        input[type='text']:focus {
            border-color: rgba(27, 108, 168, 0.8);
            box-shadow: 0 0 0 3px rgba(88, 164, 207, 0.22);
            background: #f8fcff;
        }

        .input-with-icon {
            position: relative;
            width: 100%;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            cursor: pointer;
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            box-sizing: border-box;
        }

        .password-toggle svg {
            display: block;
            width: 20px;
            height: 20px;
        }

        .password-toggle:hover {
            color: var(--accent);
        }

        .forgot-row {
            display: flex;
            justify-content: flex-end;
            margin: 8px 0 18px;
        }

        .link-button {
            border: none;
            background: transparent;
            color: var(--accent);
            font: inherit;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            padding: 0;
        }

        .link-button:hover {
            color: var(--accent-strong);
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 35, 66, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 22px;
            z-index: 40;
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            transition: opacity 180ms ease, visibility 180ms ease;
        }

        .modal-overlay.is-visible {
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
        }

        .modal-card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 24px 50px rgba(10, 35, 66, 0.22);
            transform: translateY(12px) scale(0.98);
            opacity: 0;
            transition: transform 180ms ease, opacity 180ms ease;
        }

        .modal-overlay.is-visible .modal-card {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .modal-title {
            margin: 0 0 10px;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .modal-copy {
            margin: 0 0 18px;
            color: var(--muted);
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .modal-actions .btn-ghost,
        .modal-actions .btn-primary {
            flex: 1 1 0;
        }

        .modal-floating-label {
            position: absolute;
            top: 20px;
            right: 20px;
            max-width: min(360px, calc(100% - 40px));
            border-radius: 10px;
            padding: 10px 14px;
            font-family: inherit;
            font-size: 0.9rem;
            line-height: 1.4;
            box-shadow: 0 10px 24px rgba(10, 35, 66, 0.2);
            z-index: 20;
            animation: toast-in 180ms ease-out;
            transition: opacity 220ms ease, transform 220ms ease;
        }

        .modal-floating-label.is-hiding {
            opacity: 0;
            transform: translateY(-6px);
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-floating-success {
            background: #ecfdf5;
            border: 1px solid #34d399;
            color: #065f46;
        }

        .modal-floating-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .modal-error {
            border-radius: 11px;
            padding: 12px 14px;
            margin-bottom: 14px;
            font-size: 0.9rem;
            background: rgba(248, 113, 113, 0.18);
            border: 1px solid rgba(248, 113, 113, 0.34);
            color: #7f1d1d;
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
            background: rgba(127, 29, 29, 0.12);
            border: 1px solid rgba(248, 113, 113, 0.34);
            color: #0a2342;
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
            @if ($errors->any() && ! old('forgot_password'))
                @php
                    $loginErrors = $errors->all();
                    $showSimpleInvalid = count($loginErrors) === 1 && $loginErrors[0] === 'The provided credentials do not match our records.';
                @endphp
                <div class="alert-error">
                    @if ($showSimpleInvalid)
                        Invalid credentials.
                    @else
                        <strong>Please fix the following:</strong>
                        <ul>
                            @foreach ($loginErrors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
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
                    <div class="input-with-icon">
                        <input id="password" name="password" type="password" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle" id="passwordToggle" aria-label="Show password">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 9.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5Z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="forgot-row">
                    <button type="button" class="link-button" id="forgotPasswordToggle">Forgot Password?</button>
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

    <div class="modal-overlay" id="forgotPasswordModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="forgotPasswordTitle">
            <h2 class="modal-title" id="forgotPasswordTitle">Forgot Password</h2>
            <p class="modal-copy">Enter the email address for your Event Staff account and we will send you a new password.</p>

            @if ($errors->any() && old('forgot_password'))
                <div class="modal-error">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form id="forgotPasswordForm" action="{{ route('password.email', [], false) }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="forgot_password" value="1">
                <div class="field">
                    <label for="forgot_email">Event Staff Email</label>
                    <input id="forgot_email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-ghost" id="cancelForgotPassword">Cancel</button>
                    <button type="submit" class="btn-primary" id="sendPasswordBtn">Send Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');
        const forgotPasswordToggle = document.getElementById('forgotPasswordToggle');
        const forgotPasswordModal = document.getElementById('forgotPasswordModal');
        const cancelForgotPassword = document.getElementById('cancelForgotPassword');
        const openForgotModal = {{ session('forgotPasswordModal') ? 'true' : 'false' }};

        const eyeIcon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 9.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5Z" fill="currentColor"/></svg>';
        const eyeOffIcon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M17.94 17.94C16.18 19.18 14.17 20 12 20c-5 0-9.27-3.11-11-7 1.11-2.5 2.77-4.58 4.75-6.02" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6.1 6.1C7.82 4.82 9.87 4 12 4c5 0 9.27 3.11 11 7-0.57 1.29-1.34 2.45-2.27 3.47" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9.88 9.88a2.5 2.5 0 0 0 3.53 3.53" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M2 2l20 20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';

        if (passwordToggle && passwordInput) {
            passwordToggle.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                passwordToggle.innerHTML = isPassword ? eyeOffIcon : eyeIcon;
            });
        }

        const toggleForgotModal = (show) => {
            if (!forgotPasswordModal) return;
            forgotPasswordModal.classList.toggle('is-visible', show);
            forgotPasswordModal.setAttribute('aria-hidden', show ? 'false' : 'true');
        };

        if (forgotPasswordToggle) {
            forgotPasswordToggle.addEventListener('click', () => toggleForgotModal(true));
        }

        if (cancelForgotPassword) {
            cancelForgotPassword.addEventListener('click', () => toggleForgotModal(false));
        }

        const forgotPasswordForm = document.getElementById('forgotPasswordForm');
        const sendPasswordBtn = document.getElementById('sendPasswordBtn');
        const forgotPasswordStatus = {!! session('status') ? json_encode(session('status')) : 'null' !!};

        if (sendPasswordBtn) {
            sendPasswordBtn.disabled = false;
            sendPasswordBtn.textContent = 'Send Password';
        }

        function showToast(message, type = 'success', duration = 4000, container = document.body) {
            if (!message) return;
            const toast = document.createElement('div');
            toast.className = 'modal-floating-label modal-floating-' + type + ' modal-toast';
            toast.textContent = message;
            container.appendChild(toast);
            window.setTimeout(() => toast.classList.add('is-hiding'), duration);
            window.setTimeout(() => {
                if (toast && toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, duration + 280);
        }

        if (forgotPasswordForm) {
            forgotPasswordForm.addEventListener('submit', (event) => {
                if (!forgotPasswordForm.checkValidity()) {
                    return;
                }

                event.preventDefault();

                if (sendPasswordBtn) {
                    sendPasswordBtn.disabled = true;
                    sendPasswordBtn.textContent = 'Sending...';
                }

                setTimeout(() => {
                    forgotPasswordForm.submit();
                }, 0);
            });
        }

        if (forgotPasswordStatus && forgotPasswordModal) {
            toggleForgotModal(true);
            showToast(forgotPasswordStatus, 'success', 4000, forgotPasswordModal);
        }

        if (openForgotModal && forgotPasswordModal) {
            toggleForgotModal(true);
        }

        document.addEventListener('click', (event) => {
            if (event.target === forgotPasswordModal) {
                toggleForgotModal(false);
            }
        });
    </script>
</body>
</html>
