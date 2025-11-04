<!-- Home Blade: API overview landing page (mobile-friendly) -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'VISION-API') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0d1117;
            --card: #161b22;
            --text: #e6edf3;
            --muted: #8b949e;
            --accent: #58a6ff;
            --ok: #3fb950;
            --border: #30363d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font: 16px/1.6 'Inter', sans-serif;
        }

        .wrap {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .hero {
            padding: 2.5rem 1rem;
            text-align: center;
        }

        .brand {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-bottom: 0.5rem;
        }

        .tag {
            color: var(--accent);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            margin-bottom: 0.25rem;
        }

        h1 {
            margin: 0.2rem 0 1rem;
            font-size: 2.5rem;
            line-height: 1.2;
            font-weight: 600;
            color: var(--text);
        }

        h3 {
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            color: var(--text);
        }

        .muted {
            color: var(--muted);
        }

        .grid {
            display: grid;
            gap: 20px;
            margin: 0;
        }

        @media (min-width: 768px) {
            .grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .card {
            margin-bottom: 20px;
            background: var(--card);
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border);
        }

        ul.list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ol.list {
            list-style: decimal;
            padding-left: 1.5rem;
        }

        .list li {
            margin: 0.5rem 0;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        code {
            background: #21262d;
            padding: 2px 4px;
            border-radius: 4px;
            color: var(--text);
            font-size: 0.875rem;
            overflow-wrap: anywhere;
            /* prevent overflow on small screens */
        }

        pre {
            background: #21262d;
            padding: 12px;
            border-radius: 4px;
            overflow-x: auto;
            color: var(--text);
            font-size: 0.875rem;
        }

        .pill {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            background: var(--accent);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 6px;
            white-space: nowrap;
        }

        /* Request overview list */
        .quick-links {
            display: flex;
            flex-direction: column;
            /* stack items */
            align-items: flex-start;
            /* left align */
            gap: 8px;
            margin-top: 1rem;
        }

        .quick-item {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-start;
            width: 100%;
            flex-wrap: wrap;
            /* allow wrapping on narrow screens */
        }

        .ok {
            color: var(--ok);
            font-weight: 600;
        }

        footer {
            text-align: center;
            margin: 3rem 0 2rem;
            color: var(--muted);
            font-size: 0.875rem;
        }

        .grid .card.full {
            grid-column: 1 / -1;
        }

        /* ---------- Mobile polish ---------- */
        @media (max-width: 600px) {
            .wrap {
                padding: 0 0.75rem;
            }

            h1 {
                font-size: 2rem;
            }

            .card {
                padding: 1rem;
            }

            .quick-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .pill {
                font-size: 0.7rem;
                padding: 2px 6px;
            }

            code {
                font-size: 0.82rem;
            }

            .muted {
                font-size: 0.95rem;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">

        <section class="hero card">
            <div class="brand">
                <div>
                    <div class="tag">VISION — Wearable Ecosystem for the Visually Impaired</div>
                    <h1>{{ config('app.name', 'VISION-API') }}</h1>
                </div>
            </div>
            <p class="muted">
                A Laravel-based REST API powering the VISION platform to enhance mobility, autonomy, and safety for
                visually impaired users.
                This backend connects the mobile app and website to a shared MySQL database with secure auth,
                validation, and rate-limiting.
            </p>

            <div class="grid">
                <div class="card full">
                    <h3>Request Overview</h3>
                    <div class="quick-links">
                        <div class="quick-item"><span class="pill">Base URL</span> <code>{{ url('/') }}</code>
                        </div>
                        <div class="quick-item"><span class="pill">API Prefix</span> <code>/api</code></div>
                        <div class="quick-item">
                            <span class="pill">Status</span>
                            <a href="{{ url('/api/status') }}"><code>/api/status</code></a>
                            <span id="status" class="ok"></span>
                        </div>
                        <div class="quick-item"><span class="pill">Auth</span> <code>Laravel Sanctum</code></div>
                        <div class="quick-item"><span class="pill">Format</span> <code>JSON (REST)</code></div>

                        <!-- Public endpoints -->
                        <div class="quick-item"><span class="pill">POST</span> <code>/api/login</code> <span
                                class="muted">— login (rate-limited)</span></div>
                        <div class="quick-item"><span class="pill">POST</span> <code>/api/forgot-password</code> <span
                                class="muted">— send reset link (rate-limited)</span></div>
                        <div class="quick-item"><span class="pill">POST</span> <code>/api/reset-password</code> <span
                                class="muted">— complete password reset (rate-limited)</span></div>
                        <div class="quick-item"><span class="pill">POST</span> <code>/api/signup</code> <span
                                class="muted">— create account (emails the cridentials)</span></div>
                        <div class="quick-item"><span class="pill">GET</span> <code>/api/testimonials</code> <span
                                class="muted">— public testimonials</span></div>
                        <div class="quick-item"><span class="pill">GET</span> <code>/api/admins</code> <span
                                class="muted">— list admins</span></div>

                        <!-- Authenticated (Sanctum) endpoints -->
                        <div class="quick-item"><span class="pill">GET</span> <code>/api/me</code> <span
                                class="muted">— current user (🔒)</span></div>
                        <div class="quick-item"><span class="pill">POST</span> <code>/api/logout</code> <span
                                class="muted">— revoke token (🔒)</span></div>
                        <div class="quick-item"><span class="pill">GET</span> <code>/api/users</code> <span
                                class="muted">— all users except requester (admin-only 🔒)</span></div>
                        <div class="quick-item"><span class="pill">PATCH</span> <code>/api/user</code> <span
                                class="muted">— update profile (🔒)</span></div>
                        <div class="quick-item"><span class="pill">POST</span> <code>/api/testimonial</code> <span
                                class="muted">— create/update/delete testimonial (🔒)</span></div>

                        <div class="quick-item"><span class="pill">POST</span> <code>/api/alert</code> <span
                                class="muted">— create alert (🔒)</span></div>
                        <div class="quick-item"><span class="pill">POST</span> <code>/api/solve/{alert}</code> <span
                                class="muted">— solve own alert (🔒)</span></div>
                        <div class="quick-item"><span class="pill">GET</span> <code>/api/alerts/{user}</code> <span
                                class="muted">— user’s alerts (self/admin 🔒)</span></div>

                        <div class="quick-item"><span class="pill">POST</span> <code>/api/record</code> <span
                                class="muted">— create record (🔒)</span></div>
                        <div class="quick-item"><span class="pill">GET</span> <code>/api/records/{user}</code> <span
                                class="muted">— user’s records (self/admin 🔒)</span></div>

                        <div class="quick-item">
                            <span class="pill">POST</span> <code>/api/deactivate/{user}</code>
                            <span class="muted">— deactivate (self/admin; admins can’t deactivate other admins
                                🔒)</span>
                        </div>
                        <div class="quick-item"><span class="pill">POST</span> <code>/api/activate/{user}</code> <span
                                class="muted">— activate (admin-only 🔒)</span></div>
                    </div>
                </div>

                <!-- Tech Stack under Request Overview -->
                <div class="card full">
                    <h3>Tech Stack</h3>
                    <div class="kvs">
                        <div><strong>Framework:</strong> Laravel 12</div>
                        <div><strong>Database:</strong> MySQL</div>
                        <div><strong>Auth:</strong> Sanctum</div>
                        <div><strong>Clients:</strong> React (website), React Native (mobile)</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid">
            <div class="card full">
                <h3>Features</h3>
                <ol class="list">
                    <li>Authentication &amp; authorization with Laravel Sanctum</li>
                    <li>RESTful endpoints for users, biometrics, and alerts</li>
                    <li>Data persistence &amp; aggregation via Eloquent (MySQL)</li>
                    <li>CORS/HTTPS-ready integration for web &amp; mobile</li>
                </ol>

                <h3>Security Practices</h3>
                <ol class="list">
                    <li>All sensitive routes protected by Sanctum tokens (Bearer)</li>
                    <li>CORS restricted to known origins</li>
                    <li>HTTPS/TLS recommended in production</li>
                    <li>Form Request validation on inputs</li>
                </ol>
            </div>
        </section>

        <section class="card">
            <h3>System Data Flow</h3>
            <ol class="list">
                <li>Raspberry Pi collects live processed biometric data (SPO2, Heart Rate, Galvanic Skin Resistance, and
                    Relative Humidity).</li>
                <li>React Native app reads via BLE and shows realtime metrics.</li>
                <li>Mobile app periodically sends readings to this API (HTTPS).</li>
                <li>API validates, stores, and aggregates data in MySQL.</li>
                <li>React web dashboard queries the same API for analytics.</li>
            </ol>
        </section>

        <section class="card">
            <h3>Contributors</h3>
            <ul class="list">
                <li><strong>Backend</strong>: Manar Merhi</li>
                <li><strong>Frontend (Web)</strong>: Malek Shibli</li>
                <li><strong>Mobile (React Native)</strong>: Manar Merhi</li>
                <li><strong>Embedded/Hardware</strong>: Mohammad Shaaban</li>
                <li><strong>Computer Vision &amp; ML</strong>: Mohammad El Halabi</li>
                <li><strong>Biometric Processing</strong>: Abdulrahman Nakouzi</li>
            </ul>
        </section>

        <footer>
            © 2025 VISION Project Team — Academic &amp; research use.
        </footer>
    </div>

    <script>
        (async () => {
            try {
                const res = await fetch('{{ url('/api/status') }}');
                const data = await res.json();
                const el = document.getElementById('status');
                if (res.ok && data.status === 'ok') {
                    el.textContent = 'API Healthy';
                    el.style.color = '#3fb950';
                } else {
                    el.textContent = 'API Error';
                    el.style.color = '#f85149';
                }
            } catch {
                const el = document.getElementById('status');
                if (el) {
                    el.textContent = 'API Unreachable';
                    el.style.color = '#f85149';
                }
            }
        })();
    </script>
</body>

</html>
