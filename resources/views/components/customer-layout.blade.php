<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Portal Cliente | {{ config('app.name') }}</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b7ddd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="/adminkit/img/icons/icon-48x48.png">

	<link rel="preconnect" href="https://fonts.gstatic.com/">
	<link rel="shortcut icon" href="{{ asset('adminkit/img/icons/icon-48x48.png') }}" />

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&amp;display=swap" rel="stylesheet">

	<link href="{{ asset('adminkit/css/light.css') }}" rel="stylesheet">
    <x-brand-styles />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

	<style>
		body {
			opacity: 0;
            background: #77b url('{{ asset('adminkit/img/backblue.gif') }}') !important;
            background-size: cover;
            background-attachment: fixed;
		}
        :root {
            --bs-primary: var(--primary-color, #3b7ddd);
        }
        .main {
            background: rgba(245, 247, 251, 0.85); /* Semi-transparent to show background */
        }
        .sidebar-brand-text {
            color: white;
            font-weight: 800;
        }
        .sidebar-link i, .sidebar-link svg {
            color: rgba(255, 255, 255, .5);
        }
        .sidebar-item.active .sidebar-link i, .sidebar-item.active .sidebar-link svg {
            color: #e9ecef;
        }

        /* Retractable Sidebar (Mini Mode) */
        @media (min-width: 992px) {
            #sidebar.collapsed {
                min-width: 70px;
                max-width: 70px;
            }
            #sidebar.collapsed .sidebar-link span,
            #sidebar.collapsed .sidebar-header,
            #sidebar.collapsed .sidebar-brand-text,
            #sidebar.collapsed .sidebar-user-subtitle,
            #sidebar.collapsed .sidebar-user-title,
            #sidebar.collapsed .sidebar-cta {
                display: none;
            }
            #sidebar.collapsed .sidebar-user {
                padding: 1rem 0;
            }
            #sidebar.collapsed .sidebar-user .avatar {
                margin: 0 auto;
            }
            #sidebar.collapsed .sidebar-brand {
                padding: 1rem 0;
            }
            #sidebar.collapsed .sidebar-brand img {
                max-height: 30px !important;
                margin-bottom: 0 !important;
            }
        }

        /* Modern Floating Navbar Styles */
        .floating-navbar {
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .navbar-bg {
            background: #fff;
        }
	</style>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class='sidebar-brand d-flex flex-column align-items-center py-4' href='{{ route('customer.dashboard') }}'>
                    @php
                        $tenant = \App\Models\Tenant::find(session('tenant_id'));
                        $logoUrl = $tenant->theme_config_json['logo_url'] ?? null;
                    @endphp

                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $tenant->name }}" style="max-height: 55px; width: auto;" class="mb-3">
                    @endif

					<span class="sidebar-brand-text align-middle">
						Portal<span class="text-primary-light">Cliente</span>
					</span>
				</a>

                <div class="sidebar-user">
                    <div class="d-flex justify-content-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-md bg-primary text-white rounded me-1 d-flex align-items-center justify-content-center font-bold" style="width: 40px; height: 40px;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-grow-1 ps-2">
                            <a class="sidebar-user-title dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-start">
                                <a class='dropdown-item' href='{{ route('customer.profile') }}'><i class="align-middle me-1" data-feather="user"></i> {{ __('Perfil') }}</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">{{ __('Cerrar Sesión') }}</button>
                                </form>
                            </div>

                            <div class="sidebar-user-subtitle">{{ __('Casillero') }}: {{ Auth::user()->customer?->box_number }}</div>
                        </div>
                    </div>
                </div>

				<ul class="sidebar-nav">
					<li class="sidebar-header">
						{{ __('Principal') }}
					</li>

					<li class="sidebar-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('customer.dashboard') }}'>
							<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">{{ __('Dashboard') }}</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('customer.packages') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('customer.packages') }}'>
							<i class="align-middle" data-feather="box"></i> <span class="align-middle">{{ __('Mis Paquetes') }}</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('customer.tracking') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('customer.tracking') }}'>
							<i class="align-middle text-info" data-feather="search"></i> <span class="align-middle">Rastrear Paquete (Live)</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('customer.pre-alert') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('customer.pre-alert') }}'>
							<i class="align-middle" data-feather="bell"></i> <span class="align-middle">{{ __('Pre-alertar') }}</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('customer.calculator') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('customer.calculator') }}'>
							<i class="align-middle" data-feather="percent"></i> <span class="align-middle">{{ __('Calculadora') }}</span>
						</a>
					</li>

					<li class="sidebar-header">
						{{ __('Pagos y Soporte') }}
					</li>

					<li class="sidebar-item {{ request()->routeIs('customer.invoices') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('customer.invoices') }}'>
							<i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">{{ __('Facturas') }}</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('customer.tickets.*') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('customer.tickets.index') }}'>
							<i class="align-middle" data-feather="help-circle"></i> <span class="align-middle">{{ __('Soporte') }}</span>
						</a>
					</li>

                    <li class="sidebar-header">
						{{ __('Cuenta') }}
					</li>

                    <li class="sidebar-item {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('customer.profile') }}'>
							<i class="align-middle" data-feather="user"></i> <span class="align-middle">{{ __('Mi Perfil') }}</span>
						</a>
					</li>

				</ul>

				<div class="sidebar-cta">
					<div class="sidebar-cta-content">
						<strong class="d-inline-block mb-2">Ayuda</strong>
						<div class="mb-3 text-sm">
							¿Necesitas ayuda con tu casillero? Contáctanos.
						</div>
						<div class="d-grid">
							<a href="#" class="btn btn-outline-primary">Soporte Técnico</a>
						</div>
					</div>
				</div>
			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg floating-navbar shadow-sm mx-4 mt-3">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								<div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-bold me-2 shadow-sm" style="width: 38px; height: 38px; border: 2px solid white;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="text-start d-none d-lg-block">
                                        <div class="text-dark fw-black small leading-none">{{ explode(' ', Auth::user()->name)[0] }}</div>
                                        <div class="text-muted xsmall uppercase font-bold mt-1">Mi Cuenta</div>
                                    </div>
                                </div>
							</a>
							<div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2">
								<a class='dropdown-item py-2 px-3 rounded-top-4' href='{{ route('customer.profile') }}'>
                                    <i class="align-middle me-2 text-primary" data-feather="user"></i> Mi Perfil
                                </a>
                                <a class='dropdown-item py-2 px-3' href='{{ route('customer.invoices') }}'>
                                    <i class="align-middle me-2 text-primary" data-feather="credit-card"></i> Mis Facturas
                                </a>
								<div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 px-3 text-danger rounded-bottom-4">
                                        <i class="align-middle me-2" data-feather="log-out"></i> Cerrar Sesión
                                    </button>
                                </form>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content pt-4">
				<div class="container-fluid p-0">
                    {{ $slot }}
				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="#" target="_blank"><strong>{{ config('app.name') }}</strong></a> &copy; {{ date('Y') }}
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="#" target="_blank">Términos</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="#" target="_blank">Privacidad</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="{{ asset('adminkit/js/app.js') }}"></script>
    <script>
        // Proxy feather.replace to prevent crashes from vendor code
        if (typeof feather !== 'undefined' && feather.replace) {
            const originalReplace = feather.replace;
            feather.replace = function(options) {
                try {
                    if (feather.icons) {
                        const elements = document.querySelectorAll('[data-feather]');
                        elements.forEach(el => {
                            const iconName = el.getAttribute('data-feather');
                            if (iconName && !feather.icons[iconName]) {
                                el.removeAttribute('data-feather');
                            }
                        });
                    }
                    return originalReplace.call(this, options);
                } catch (e) {}
            };
        }

        document.addEventListener('livewire:navigated', () => {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                setTimeout(() => {
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                }, 10);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
