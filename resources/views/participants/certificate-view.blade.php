<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Certificate</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #eef4fb;
            color: #0f172a;
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; background: #eef4fb; }
        body { padding: 0; }

        .page-shell {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 16px 32px;
        }

        .page-title {
            max-width: 900px;
            width: 100%;
            margin-bottom: 18px;
            text-align: center;
        }

        .page-title h1 {
            margin: 0;
            font-size: clamp(1.5rem, 2.3vw, 2.2rem);
            letter-spacing: -0.03em;
            line-height: 1.1;
        }

        .page-title p {
            margin: 12px auto 0;
            max-width: 760px;
            font-size: 0.96rem;
            line-height: 1.7;
            color: #475569;
        }

        .certificate-stack {
            width: 100%;
            max-width: 980px;
            display: grid;
            gap: 28px;
        }

        .certificate-card {
            width: 100%;
            min-height: 68vh;
            padding: 32px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid #dbe8f6;
            border-radius: 28px;
            box-shadow: 0 28px 80px rgba(15, 40, 75, 0.12);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .certificate-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.12), transparent 32%), radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.1), transparent 24%);
            pointer-events: none;
        }

        .certificate-content {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 20px;
        }

        .certificate-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(14, 165, 233, 0.12);
            color: #0369a1;
            font-size: 0.85rem;
            font-weight: 700;
            width: fit-content;
        }

        .certificate-heading {
            font-size: clamp(2.4rem, 4vw, 3.4rem);
            line-height: 1.02;
            margin: 0;
            font-weight: 800;
            letter-spacing: -0.05em;
            color: #0f172a;
        }

        .subheading {
            margin: 0;
            font-size: 1rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #3b82f6;
            font-weight: 700;
        }

        .certificate-intro {
            max-width: 760px;
            font-size: 1rem;
            line-height: 1.8;
            color: #475569;
        }

        .participant-name {
            margin: 0;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
        }

        .certificate-description {
            font-size: 1rem;
            line-height: 1.85;
            color: #475569;
            max-width: 780px;
        }

        .certificate-footer {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(15, 23, 42, 0.08);
        }

        .certificate-footer-item {
            display: grid;
            gap: 4px;
        }

        .certificate-footer-item span:first-child {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #0ea5e9;
        }

        .certificate-footer-item span:last-child {
            display: block;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
        }

        .certificate-signature {
            display: grid;
            gap: 6px;
            margin-top: 28px;
        }

        .certificate-signature-line {
            width: 180px;
            height: 1px;
            background: #cbd5e1;
        }

        .certificate-signature-title {
            font-size: 0.88rem;
            color: #475569;
        }

        .print-note {
            margin: 0;
            font-size: 0.88rem;
            color: #64748b;
        }

        .top-tip {
            width: 100%;
            max-width: 980px;
            margin: 0 auto 12px;
            padding: 14px 18px;
            border-radius: 18px;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            color: #0f172a;
            font-size: 0.95rem;
            line-height: 1.6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .top-tip strong {
            color: #1d4ed8;
        }

        @media (max-width: 760px) {
            .certificate-card {
                padding: 22px;
                min-height: auto;
            }

            .certificate-footer {
                grid-template-columns: 1fr;
            }

            .certificate-heading {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="page-title">
            <h1>Certificate Viewer</h1>
            <p>Long press the certificate card or use your browser share button to save it to your device.</p>
        </div>

        <div class="top-tip">
            <strong>Tip:</strong> Long press the certificate image to save it to your device, or use your browser share/save options.
        </div>

        <div class="certificate-stack">
            @foreach ($pages as $page)
                <section class="certificate-card">
                    <div class="certificate-content">
                        <div>
                            <span class="certificate-badge">Certificate of {{ $page['certificateType'] }}</span>
                        </div>
                        <div>
                            <p class="subheading">This certificate is awarded to</p>
                            <h2 class="participant-name">{{ $participant->name }}</h2>
                        </div>
                        <div class="certificate-description">
                            <p>{{ $page['description'] }}</p>
                        </div>

                        <div class="certificate-footer">
                            <div class="certificate-footer-item">
                                <span>Event</span>
                                <span>{{ $participant->event->title }}</span>
                            </div>
                            <div class="certificate-footer-item">
                                <span>Date</span>
                                <span>{{ $eventDate }}</span>
                            </div>
                            <div class="certificate-footer-item">
                                <span>Location</span>
                                <span>{{ $eventLocation }}</span>
                            </div>
                        </div>

                        <div class="certificate-signature">
                            <div class="certificate-signature-line"></div>
                            <div class="certificate-signature-title">Event Organizer</div>
                        </div>
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</body>
</html>
