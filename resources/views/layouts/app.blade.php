<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'NuxGame')</title>
    <style>
        :root {
            --bg: #0f1419;
            --surface: #1a222c;
            --border: #2c3846;
            --text: #e8eef4;
            --muted: #8b9aab;
            --accent: #3dba8c;
            --accent-hover: #4fd4a1;
            --danger: #e06b6b;
            --warn: #d4a84b;
            --win: #3dba8c;
            --lose: #e06b6b;
            --radius: 10px;
            --font: "Segoe UI", system-ui, -apple-system, sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text);
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, #1e3a32 0%, transparent 55%),
                var(--bg);
            line-height: 1.5;
        }

        .wrap {
            width: min(640px, calc(100% - 2rem));
            margin: 0 auto;
            padding: 2.5rem 0 3rem;
        }

        .brand {
            font-size: 0.8rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent);
            margin: 0 0 0.75rem;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 650;
            margin: 0 0 0.5rem;
        }

        h2 {
            font-size: 1.1rem;
            margin: 1.5rem 0 0.75rem;
            color: var(--text);
        }

        p, li { color: var(--muted); }

        a { color: var(--accent); }

        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.35rem;
            margin: 1rem 0;
        }

        .meta {
            font-size: 0.92rem;
            word-break: break-all;
        }

        .meta strong { color: var(--text); font-weight: 600; }

        label {
            display: block;
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 0.35rem;
        }

        input[type="text"] {
            width: 100%;
            padding: 0.65rem 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg);
            color: var(--text);
            font: inherit;
        }

        input[type="text"]:focus {
            outline: 2px solid color-mix(in srgb, var(--accent) 55%, transparent);
            border-color: var(--accent);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin: 1rem 0;
        }

        .actions form { margin: 0; }

        button, .btn {
            display: inline-block;
            padding: 0.6rem 1rem;
            border: 0;
            border-radius: 8px;
            background: var(--accent);
            color: #062018;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        button:hover, .btn:hover { background: var(--accent-hover); }

        button.secondary, .btn.secondary {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }

        button.secondary:hover, .btn.secondary:hover {
            border-color: var(--muted);
            background: color-mix(in srgb, var(--surface) 80%, white 8%);
        }

        button.danger {
            background: transparent;
            color: var(--danger);
            border: 1px solid color-mix(in srgb, var(--danger) 45%, var(--border));
        }

        button.danger:hover {
            background: color-mix(in srgb, var(--danger) 15%, transparent);
        }

        .errors {
            list-style: none;
            padding: 0.75rem 1rem;
            margin: 0 0 1rem;
            background: color-mix(in srgb, var(--danger) 12%, var(--surface));
            border: 1px solid color-mix(in srgb, var(--danger) 40%, var(--border));
            border-radius: 8px;
            color: #f0c0c0;
        }

        .result-win { color: var(--win); font-weight: 650; }
        .result-lose { color: var(--lose); font-weight: 650; }

        .history-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
            color: var(--muted);
        }

        .history-item:last-child { border-bottom: 0; }

        .status-code {
            font-size: 3rem;
            font-weight: 700;
            color: var(--warn);
            margin: 0 0 0.25rem;
            line-height: 1;
        }
    </style>
</head>
<body>
    <main class="wrap">
        <p class="brand">NuxGame</p>
        @yield('content')
    </main>
</body>
</html>
