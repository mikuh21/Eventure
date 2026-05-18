<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'EventPulse')); ?></title>
    <style>
        :root {
            --bg-ink: #0c1217;
            --bg-night: #111f2b;
            --panel: #f9fafb;
            --brand: #0f766e;
            --brand-strong: #0b5f59;
            --accent: #f59e0b;
            --text-main: #0f172a;
            --text-soft: #334155;
            --white: #ffffff;
            --ring: rgba(15, 118, 110, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at 10% 20%, rgba(245, 158, 11, 0.18), transparent 38%),
                radial-gradient(circle at 90% 10%, rgba(15, 118, 110, 0.22), transparent 35%),
                linear-gradient(150deg, var(--bg-night), var(--bg-ink));
            min-height: 100vh;
        }

        .shell {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px 48px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 36px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--white);
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .logo-mark {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), #ef4444);
            position: relative;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
        }

        .logo-mark::after {
            content: "";
            position: absolute;
            inset: 8px;
            border: 2px solid rgba(255, 255, 255, 0.9);
            border-radius: 6px;
        }

        .top-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-ghost {
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.06);
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.14);
        }

        .btn-primary {
            color: var(--white);
            background: linear-gradient(135deg, var(--brand), var(--brand-strong));
            box-shadow: 0 6px 18px rgba(15, 118, 110, 0.35);
        }

        .btn-primary:hover {
            box-shadow: 0 8px 24px rgba(15, 118, 110, 0.45);
        }

        .hero {
            background: var(--panel);
            border-radius: 18px;
            padding: 28px;
            display: grid;
            grid-template-columns: 1.25fr 1fr;
            gap: 20px;
            box-shadow: 0 22px 50px rgba(0, 0, 0, 0.25);
            animation: fadeUp 0.55s ease both;
        }

        .tag {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--brand);
            background: rgba(15, 118, 110, 0.1);
            border-radius: 999px;
            padding: 6px 10px;
            margin-bottom: 12px;
        }

        h1 {
            margin: 0;
            line-height: 1.15;
            font-size: clamp(28px, 4vw, 44px);
        }

        .lead {
            margin: 14px 0 0;
            color: var(--text-soft);
            max-width: 56ch;
        }

        .hero-actions {
            margin-top: 22px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .panel {
            background: linear-gradient(160deg, #e2e8f0, #ffffff);
            border-radius: 14px;
            border: 1px solid #dbe4ef;
            padding: 16px;
        }

        .metric {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #cbd5e1;
            font-size: 14px;
        }

        .metric:last-child {
            border-bottom: none;
        }

        .metric strong {
            font-size: 16px;
            color: #0f172a;
        }

        .footer-note {
            margin-top: 14px;
            font-size: 12px;
            color: #64748b;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .shell {
                padding: 18px 14px 24px;
            }

            .hero {
                padding: 18px;
                border-radius: 14px;
            }

            .topbar {
                margin-bottom: 18px;
            }
        }
    </style>
</head>
<body>
<div class="shell">
    <header class="topbar">
        <div class="logo">
            <span class="logo-mark" aria-hidden="true"></span>
            <span>EventPulse</span>
        </div>
        <nav class="top-actions">
            <a class="btn btn-ghost" href="<?php echo e(route('participants.public.events')); ?>">Browse Events</a>
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isAdmin()): ?>
                    <a class="btn btn-primary" href="<?php echo e(route('admin.dashboard')); ?>">Open Dashboard</a>
                <?php else: ?>
                    <a class="btn btn-primary" href="<?php echo e(route('participants.public.events')); ?>">Open Events</a>
                <?php endif; ?>
            <?php else: ?>
                <a class="btn btn-primary" href="<?php echo e(route('login')); ?>">Admin Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="hero">
        <section>
            <span class="tag">Event Management System</span>
            <h1>Run events, track attendance, and launch post-event surveys from one control center.</h1>
            <p class="lead">
                EventPulse gives your team a single workflow for registrations, digital IDs, analytics, and submissions across standard events and conferences.
            </p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="<?php echo e(route('participants.public.events')); ?>">View Public Events</a>
                <?php if(auth()->guard()->check()): ?>
                    <a class="btn btn-ghost" href="<?php echo e(route('events.index')); ?>">Manage Events</a>
                <?php else: ?>
                    <a class="btn btn-ghost" href="<?php echo e(route('login')); ?>">Team Sign In</a>
                <?php endif; ?>
            </div>
        </section>

        <aside class="panel" aria-label="Feature highlights">
            <div class="metric"><span>Event Types</span><strong>Standard + Conference</strong></div>
            <div class="metric"><span>Attendance Tracking</span><strong>Digital ID Scan</strong></div>
            <div class="metric"><span>Survey Workflow</span><strong>Post-event Activation</strong></div>
            <div class="metric"><span>Analytics</span><strong>Monthly + Yearly Filters</strong></div>
            <p class="footer-note">Use the admin dashboard to monitor participant and evaluation counts in real time.</p>
        </aside>
    </main>
</div>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/welcome.blade.php ENDPATH**/ ?>