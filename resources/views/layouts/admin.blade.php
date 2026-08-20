<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') · Cafe Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #14171f;
            --ink-soft: #565c6b;
            --indigo: #4338ca;
            --indigo-light: #eef0fd;
            --border: #e5e7eb;
            --bg: #f7f8fa;
            --white: #ffffff;
            --danger: #dc2626;
            --success: #16a34a;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--ink);
            display: flex;
            min-height: 100vh;
        }
        h1, h2, h3, .brand { font-family: 'Space Grotesk', sans-serif; }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: var(--white);
            border-right: 1px solid var(--border);
            padding: 24px 16px;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            display: flex;
            flex-direction: column;
        }
        .brand {
            font-size: 20px;
            font-weight: 700;
            color: var(--indigo);
            padding: 0 8px 24px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--ink-soft);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .nav-link:hover { background: var(--bg); color: var(--ink); }
        .nav-link.active { background: var(--indigo-light); color: var(--indigo); }
        .sidebar-footer { margin-top: auto; }
        .logout-btn {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            background: var(--white);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--ink-soft);
            cursor: pointer;
        }
        .logout-btn:hover { background: var(--bg); }

        /* Main */
        .main {
            margin-left: 240px;
            flex: 1;
            padding: 32px 40px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }
        .page-header h1 { font-size: 24px; font-weight: 700; }
        .page-header p { color: var(--ink-soft); font-size: 14px; margin-top: 4px; }

        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-primary { background: var(--indigo); color: var(--white); }
        .btn-primary:hover { background: #3730a3; }
        .btn-outline { background: var(--white); border: 1px solid var(--border); color: var(--ink); }
        .btn-outline:hover { background: var(--bg); }
        .btn-danger-text { background: none; border: none; color: var(--danger); font-size: 13px; cursor: pointer; font-weight: 600; }

        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px 10px; font-size: 14px; border-bottom: 1px solid var(--border); }
        th { color: var(--ink-soft); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: .02em; }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background: #dcfce7; color: var(--success); }
        .badge-muted { background: #f1f2f4; color: var(--ink-soft); }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert-success { background: #ecfdf3; color: var(--success); border: 1px solid #b8f0c9; }
        .alert-error { background: #fef2f2; color: var(--danger); border: 1px solid #fbd0d0; }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(20,23,31,0.4);
            align-items: center;
            justify-content: center;
            z-index: 50;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--white);
            border-radius: 14px;
            padding: 28px;
            width: 420px;
            max-height: 85vh;
            overflow-y: auto;
        }
        .modal h3 { font-size: 18px; margin-bottom: 18px; }
        .field { margin-bottom: 14px; }
        .field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .field input, .field select, .field textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }
        .field-error { color: var(--danger); font-size: 12px; margin-top: 4px; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .checkbox-field { display: flex; align-items: center; gap: 8px; }
        .checkbox-field input { width: auto; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">Cafe Admin</div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Menu Items</a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
            <a href="{{ route('admin.staff.index') }}" class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">Staff</a>
            <a href="{{ route('admin.delivery.index') }}" class="nav-link {{ request()->routeIs('admin.delivery.*') ? 'active' : '' }}">Delivery Riders</a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout-btn" type="submit">Log out</button>
            </form>
        </div>
    </aside>

    <main class="main">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>