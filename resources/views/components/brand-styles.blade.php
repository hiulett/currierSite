@php
    $tenantId = session('tenant_id');
    $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : null;

    if (!$tenant) {
        $tenant = \App\Models\Tenant::first(); // Fallback for safety
    }

    $config = $tenant->theme_config_json ?? [];
    $primary = $config['primary_color'] ?? '#3b7ddd';
    $secondary = $config['secondary_color'] ?? '#6c757d';
    $font = $config['font_family'] ?? 'inter';
    $theme = $config['theme_mode'] ?? 'light';

    if (!function_exists('hexToRgb')) {
        function hexToRgb($hex) {
            $hex = str_replace("#", "", $hex);
            if(strlen($hex) == 3) {
                $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                $b = hexdec(substr($hex,2,1).substr($hex,2,1));
            } elseif(strlen($hex) == 6) {
                $r = hexdec(substr($hex,0,2));
                $g = hexdec(substr($hex,2,2));
                $b = hexdec(substr($hex,4,2));
            } else {
                return "59, 125, 221"; // Default fallback (AdminKit blue)
            }
            return "$r, $g, $b";
        }
    }

    $primaryRgb = hexToRgb($primary);

    $themes = [
        'light' => [
            'bg' => '#f5f7fb',
            'sidebar' => '#222e3c',
            'card' => '#ffffff',
            'text' => '#495057',
            'text-heading' => '#212529',
            'border' => '#dee2e6',
            'navbar' => '#ffffff'
        ],
        'dark' => [
            'bg' => '#1b223c',
            'sidebar' => '#11172b',
            'card' => '#222b45',
            'text' => '#d1d1e0',
            'text-heading' => '#ffffff',
            'border' => '#2e3a59',
            'navbar' => '#222b45'
        ],
        'slate' => [
            'bg' => '#0f172a',
            'sidebar' => '#1e293b',
            'card' => '#1e293b',
            'text' => '#94a3b8',
            'text-heading' => '#f1f5f9',
            'border' => '#334155',
            'navbar' => '#1e293b'
        ],
        'oceanic' => [
            'bg' => '#0c4a6e',
            'sidebar' => '#082f49',
            'card' => '#075985',
            'text' => '#f0f9ff',
            'text-heading' => '#7dd3fc',
            'border' => '#0369a1',
            'navbar' => '#082f49'
        ]
    ];

    $activeTheme = $themes[$theme] ?? $themes['light'];
@endphp

<style>
    :root {
        --primary-color: {{ $primary }};
        --secondary-color: {{ $secondary }};
        --primary-rgb: {{ $primaryRgb }};
        --bs-primary: {{ $primary }};
        --bs-secondary: {{ $secondary }};
    }

    /* 1. Base Theme Mode (Lowest Priority) */
    body {
        background: {{ $activeTheme['bg'] }} !important;
        color: {{ $activeTheme['text'] }} !important;
        font-family: '{{ $font }}', sans-serif !important;
    }

    .card {
        background-color: {{ $activeTheme['card'] }};
        border-color: {{ $activeTheme['border'] }};
    }

    .card-title, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
        color: {{ $activeTheme['text-heading'] }};
    }

    .navbar-bg {
        background: {{ $activeTheme['navbar'] }} !important;
    }

    .footer {
        background: {{ $activeTheme['navbar'] }} !important;
        border-top: 1px solid {{ $activeTheme['border'] }};
    }

    /* 2. AdminKit & Bootstrap Overrides */
    .btn {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        padding: 0.6rem 1.25rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white !important;
    }
    .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
        background-color: var(--primary-color);
        filter: brightness(85%);
        border-color: var(--primary-color);
        color: white !important;
    }

    .btn-light {
        background: #f8f9fa !important;
        border-color: #dee2e6 !important;
        color: #212529 !important;
    }

    .sidebar-brand, .sidebar-brand:hover {
        background: {{ $activeTheme['sidebar'] }};
    }

    .sidebar, .sidebar-content {
        background: {{ $activeTheme['sidebar'] }} !important;
    }

    .sidebar-item.active .sidebar-link {
        color: white !important;
        background: rgba(255, 255, 255, .075);
        border-left-color: var(--primary-color);
    }

    .sidebar-link i, .sidebar-link svg {
        display: inline-block !important;
        vertical-align: middle !important;
        margin-right: .5rem !important;
    }

    /* 3. Utility Classes (Highest Priority via !important) */
    .bg-primary {
        background-color: var(--primary-color) !important;
    }

    .bg-dark {
        background-color: #212529 !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-white {
        color: #ffffff !important;
    }

    .text-white-50 {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    /* 4. Special Fixes */
    ::placeholder {
        color: rgba(0, 0, 0, 0.4) !important;
        font-weight: 400;
    }

    @if($theme != 'light')
        ::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        .table {
            color: {{ $activeTheme['text'] }} !important;
        }
        .table thead th {
            color: {{ $activeTheme['text-heading'] }} !important;
            border-bottom-color: {{ $activeTheme['border'] }} !important;
        }
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: {{ $activeTheme['border'] }} !important;
            color: {{ $activeTheme['text'] }} !important;
        }
    @endif

    .badge-primary-light {
        background-color: rgba({{ $primaryRgb }}, 0.1);
        color: var(--primary-color);
    }

    .stat {
        background: rgba({{ $primaryRgb }}, 0.1);
        color: var(--primary-color);
    }

    /* Timeline Modern Styles */
    .timeline-modern .timeline-item:last-child {
        padding-bottom: 0 !important;
    }
    .timeline-modern .timeline-item .position-absolute.bg-primary {
        z-index: 1;
    }

    /* Horizontal Progress Tracking */
    .tracking-progress {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 15px 0 5px 0;
        padding: 0 5px;
    }
    .tracking-progress::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 10px;
        right: 10px;
        height: 2px;
        background: #dee2e6;
        z-index: 0;
        transform: translateY(-50%);
    }
    .tracking-step {
        width: 12px;
        height: 12px;
        background: #dee2e6;
        border: 2px solid white;
        border-radius: 50%;
        z-index: 1;
        position: relative;
    }
    .tracking-step.active {
        background: var(--primary-color);
        transform: scale(1.3);
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.2);
    }
    .tracking-step.completed {
        background: var(--primary-color);
    }
    .tracking-label-row {
        display: flex;
        justify-content: space-between;
        margin-top: 2px;
    }
    .tracking-label {
        font-size: 0.55rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #adb5bd;
        width: 20%;
        text-align: center;
    }
    .tracking-label:first-child { text-align: left; }
    .tracking-label:last-child { text-align: right; }
    .tracking-label.active {
        color: var(--primary-color);
    }

    /* Fix for icons and buttons text - FORCE REFRESH */
    svg.align-middle, i.align-middle {
        width: 16px !important;
        height: 16px !important;
        stroke-width: 2.5px;
        stroke: currentColor;
        fill: none;
        display: inline-block !important;
        vertical-align: middle !important;
    }
</style>

@if($font == 'poppins')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
@elseif($font == 'inter')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
@elseif($font == 'roboto')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
@endif
