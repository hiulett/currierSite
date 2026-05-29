<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ config('app.name') ?? 'LogiSaaS' }} | Dashboard</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b7ddd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="/adminkit/img/icons/icon-48x48.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
	<link href="{{ asset('adminkit/css/light.css') }}" rel="stylesheet">
    <x-brand-styles />

	<style>
        .sidebar-brand-text {
            color: white;
            font-weight: 800;
        }
        :root {
            --bs-primary: var(--primary-color, #3b7ddd);
        }

        /* Retractable Sidebar Enhancements */
        @media (min-width: 992px) {
            #sidebar.collapsed {
                margin-left: -260px;
            }
            #sidebar.collapsed + .main {
                margin-left: 0;
            }
        }

        #sidebar {
            transition: margin-left 0.35s ease-in-out, left 0.35s ease-in-out, width 0.35s ease-in-out;
        }

        /* Modern Floating Navbar Styles */
        .floating-navbar {
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            z-index: 1050; /* Ensure it stays above dashboard controls */
        }
        .navbar-bg {
            background: #fff;
        }

        /* Fullscreen Card Styling */
        .card-fullscreen {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 9999 !important;
            margin: 0 !important;
            border-radius: 0 !important;
        }
        .card-fullscreen .card-body {
            height: calc(100vh - 60px) !important;
            overflow-y: auto;
        }
        .card-fullscreen .chart {
            height: 100% !important;
        }

        .cursor-grab { cursor: grab; }
        .cursor-grabbing { cursor: grabbing; }
	</style>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
    @if(session()->has('impersonate_tenant_id'))
        <div class="bg-warning text-dark py-1 px-4 text-center fw-bold small uppercase tracking-tighter shadow-sm d-flex align-items-center justify-content-center" style="font-size: 0.75rem; min-height: 35px;">
            <i data-feather="eye" class="me-2" style="width: 14px; height: 14px;"></i>
            <span>Modo Visualización: Estás viendo el panel de <strong>{{ config('app.name') }}</strong></span>
            <a href="{{ route('super.stop-impersonating') }}" class="btn btn-dark btn-sm ms-3 py-0 px-2 fw-black" style="font-size: 0.65rem; height: 22px; line-height: 22px;">
                SALIR MODO DIOS
            </a>
        </div>
    @endif
    @php
        $navAlerts = $navAlerts ?? [];
        $totalNavAlerts = $totalNavAlerts ?? 0;
    @endphp
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class='sidebar-brand d-flex flex-column align-items-center py-4' href='{{ route('dashboard') }}'>
                    @php
                        $tenantId = session('tenant_id') ?? (Auth::check() ? Auth::user()->tenant_id : null);
                        $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : \App\Models\Tenant::first();
                        $logoUrl = $tenant->theme_config_json['logo_url'] ?? null;
                    @endphp

                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $tenant->name ?? 'Logo' }}" style="max-height: 55px; width: auto; border-radius: 8px;" class="mb-3 shadow-sm bg-white p-1">
                    @endif

                    <span class="sidebar-brand-text align-middle">
                        {{ $tenant->name ?? config('app.name') }}
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
                                <a class='dropdown-item' href='#'><i class="align-middle me-1" data-feather="user"></i> Perfil</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Cerrar Sesión</button>
                                </form>
                            </div>

                            <div class="sidebar-user-subtitle">{{ Auth::user()->role ?? 'Administrador' }}</div>
                        </div>
                    </div>
                </div>

				<ul class="sidebar-nav">
					<li class="sidebar-header">
						{{ __('Principal') }}
					</li>

					<li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('dashboard') }}'>
							<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">{{ __('Dashboard') }}</span>
						</a>
					</li>

                    <!-- PROCESO 1: ENTRADA -->
					<li class="sidebar-header">
						Entrada de Carga
					</li>

					@can('logistics.receive')
                    <li class="sidebar-item {{ request()->routeIs('logistics.smart-reception') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.smart-reception') }}'>
							<i class="align-middle text-primary" data-feather="zap"></i> <span class="align-middle">Recepción de Paquetes</span>
						</a>
					</li>
                    <li class="sidebar-item {{ request()->routeIs('logistics.receive-manifest') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.receive-manifest') }}'>
							<i class="align-middle text-warning" data-feather="file-text"></i> <span class="align-middle">Control Manifiestos</span>
						</a>
					</li>
					@endcan

                    <!-- PROCESO 2: ALMACENAMIENTO -->
                    <li class="sidebar-header">
						Gestión de Bodega
					</li>

					@can('logistics.inventory')
					<li class="sidebar-item {{ request()->routeIs('logistics.inventory') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.inventory') }}'>
							<i class="align-middle" data-feather="box"></i> <span class="align-middle">Inventario Activo</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('logistics.tracking') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.tracking') }}'>
							<i class="align-middle text-info" data-feather="search"></i> <span class="align-middle">Rastreo Global</span>
						</a>
					</li>
					@endcan

					@can('logistics.repack')
					<li class="sidebar-item {{ request()->routeIs('logistics.repack') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.repack') }}'>
							<i class="align-middle" data-feather="package"></i> <span class="align-middle">Reempaque / Consolidación</span>
						</a>
					</li>
					@endcan

                    <!-- PROCESO 3: SALIDAS Y ENTREGAS -->
                    <li class="sidebar-header">
						Salidas y Entrega
					</li>

					@can('logistics.shipments')
					<li class="sidebar-item {{ request()->routeIs('logistics.shipments.*') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.shipments.index') }}'>
							<i class="align-middle" data-feather="truck"></i> <span class="align-middle">Embarques (Outbound)</span>
						</a>
					</li>
					@endcan

					@can('logistics.delivery')
					<li class="sidebar-item {{ request()->routeIs('logistics.delivery') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.delivery') }}'>
							<i class="align-middle" data-feather="map-pin"></i> <span class="align-middle">Última Milla (Delivery)</span>
						</a>
					</li>
					@endcan

                    @can('logistics.counter')
                    <li class="sidebar-item {{ request()->routeIs('logistics.counter') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.counter') }}'>
							<i class="align-middle" data-feather="box"></i> <span class="align-middle">Entrega en Counter</span>
						</a>
					</li>
					@endcan

                    <!-- ADMINISTRACIÓN -->
                    <li class="sidebar-header">
						Relaciones y Soporte
					</li>

                    @can('customers.view')
                    <li class="sidebar-item {{ request()->routeIs('logistics.customers') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.customers') }}'>
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Base de Clientes</span>
						</a>
					</li>
					@endcan

                    @can('tickets.manage')
                    <li class="sidebar-item {{ request()->routeIs('logistics.tickets') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.tickets') }}'>
							<i class="align-middle" data-feather="message-square"></i> <span class="align-middle">Soporte (Tickets)</span>
						</a>
					</li>
					@endcan

                    @can('logistics.inventory')
                    <li class="sidebar-item {{ request()->routeIs('logistics.lockers') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.lockers') }}'>
							<i class="align-middle" data-feather="grid"></i> <span class="align-middle">Casilleros Físicos</span>
						</a>
					</li>
					@endcan

                    <!-- FINANZAS -->
                    <li class="sidebar-header">
						Administración Financiera
					</li>

					@can('billing.view')
					<li class="sidebar-item {{ request()->routeIs('billing.index') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('billing.index') }}'>
							<i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Facturación</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('billing.statement') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('billing.statement') }}'>
							<i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Estados de Cuenta</span>
						</a>
					</li>
					@endcan

                    @can('logistics.reports')
                    <li class="sidebar-item {{ request()->routeIs('logistics.reports') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('logistics.reports') }}'>
							<i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Reportes de Negocio</span>
						</a>
					</li>
					@endcan

                    <!-- CONFIGURACIÓN -->
                    <li class="sidebar-header">
						Configuración
					</li>

                    @can('settings.brand')
                    <li class="sidebar-item {{ request()->routeIs('builder.brand') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('builder.brand') }}'>
							<i class="align-middle" data-feather="layout"></i> <span class="align-middle">Identidad Visual</span>
						</a>
					</li>
					@endcan

                    @can('settings.general')
                    <li class="sidebar-item {{ request()->routeIs('builder.warehouses') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('builder.warehouses') }}'>
							<i class="align-middle" data-feather="home"></i> <span class="align-middle">Gestión de Bodegas</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('builder.statuses') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('builder.statuses') }}'>
							<i class="align-middle" data-feather="list"></i> <span class="align-middle">Estados de Carga</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('builder.loyalty') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('builder.loyalty') }}'>
							<i class="align-middle" data-feather="award"></i> <span class="align-middle">Niveles de Cliente</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('builder.promotions') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('builder.promotions') }}'>
							<i class="align-middle" data-feather="tag"></i> <span class="align-middle">Promociones</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('builder.users') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('builder.users') }}'>
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Usuarios y Roles</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('builder.general') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('builder.general') }}'>
							<i class="align-middle" data-feather="settings"></i> <span class="align-middle">Ajustes Generales</span>
						</a>
					</li>
					@endcan

                    @if(Auth::user()->role === 'superadmin')
                        <li class="sidebar-header">
                            Root Control
                        </li>
                        <li class="sidebar-item">
                            <a class='sidebar-link' href='{{ route('super.dashboard') }}' style="background: rgba(59, 125, 221, 0.1); color: #3b7ddd;">
                                <i class="align-middle text-primary" data-feather="shield"></i> <span class="align-middle fw-bold">Panel Super Admin</span>
                            </a>
                        </li>
                    @endif
				</ul>

				</ul>

				<div class="sidebar-cta">
					<div class="sidebar-cta-content">
						<strong class="d-inline-block mb-2">LogiSaaS Pro</strong>
						<div class="mb-3 text-sm">
							Sistema integral de logística multi-tenant.
						</div>
						<div class="d-grid">
							<a href="#" class="btn btn-primary">Documentación</a>
						</div>
					</div>
				</div>
			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg floating-navbar shadow-sm mx-4 mt-2">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									@if($totalNavAlerts > 0)
										<span class="indicator">{{ $totalNavAlerts }}</span>
									@endif
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0 shadow-lg border-0 rounded-4 overflow-hidden mt-2" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header py-3 bg-primary text-white font-black small uppercase tracking-widest text-center">
									{{ $totalNavAlerts }} {{ __('Notificaciones Pendientes') }}
								</div>
								<div class="list-group">
									@if(isset($navAlerts['overdue']))
										<a href="{{ route('billing.index', ['filter_status' => 'overdue']) }}" class="list-group-item border-0 border-bottom">
											<div class="row g-0 align-items-center">
												<div class="col-2 text-center">
													<i class="text-danger" data-feather="alert-circle"></i>
												</div>
												<div class="col-10">
													<div class="text-dark fw-bold">{{ __('Facturas Vencidas') }}</div>
													<div class="text-muted small mt-1">{{ __('Hay :count facturas sin cobrar.', ['count' => $navAlerts['overdue']]) }}</div>
												</div>
											</div>
										</a>
									@endif
									@if(isset($navAlerts['prealerts']))
										<a href="{{ route('logistics.inventory', ['filter_status' => 'prealert']) }}" class="list-group-item border-0 border-bottom">
											<div class="row g-0 align-items-center">
												<div class="col-2 text-center">
													<i class="text-primary" data-feather="bell"></i>
												</div>
												<div class="col-10">
													<div class="text-dark fw-bold">{{ __('Nuevas Pre-alertas') }}</div>
													<div class="text-muted small mt-1">{{ __('Llegaron :count avisos de clientes.', ['count' => $navAlerts['prealerts']]) }}</div>
												</div>
											</div>
										</a>
									@endif
									@if(isset($navAlerts['tickets']))
										<a href="{{ route('logistics.tickets') }}" class="list-group-item border-0">
											<div class="row g-0 align-items-center">
												<div class="col-2 text-center">
													<i class="text-warning" data-feather="message-square"></i>
												</div>
												<div class="col-10">
													<div class="text-dark fw-bold">{{ __('Soporte al Cliente') }}</div>
													<div class="text-muted small mt-1">{{ __(':count tickets esperan respuesta.', ['count' => $navAlerts['tickets']]) }}</div>
												</div>
											</div>
										</a>
									@endif
									@if(isset($navAlerts['new_customers']))
										<a href="{{ route('logistics.customers', ['filter' => 'new']) }}" class="list-group-item border-0">
											<div class="row g-0 align-items-center">
												<div class="col-2 text-center">
													<i class="text-success" data-feather="user-plus"></i>
												</div>
												<div class="col-10">
													<div class="text-dark fw-bold">{{ __('Nuevos Miembros') }}</div>
													<div class="text-muted small mt-1">{{ __('Hay :count nuevos clientes registrados.', ['count' => $navAlerts['new_customers']]) }}</div>
												</div>
											</div>
										</a>
									@endif
								</div>
								<div class="dropdown-menu-footer py-2 bg-light text-center border-top">
									<a href="{{ route('dashboard') }}" class="text-muted xsmall font-bold uppercase">{{ __('Ver todo') }}</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								<div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-center font-bold me-2 shadow-sm" style="width: 38px; height: 38px; border: 2px solid white;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="text-start d-none d-lg-block">
                                        <div class="text-dark fw-black small leading-none">{{ explode(' ', Auth::user()->name)[0] }}</div>
                                        <div class="text-muted xsmall uppercase font-bold mt-1">{{ Auth::user()->role ?? 'Admin' }}</div>
                                    </div>
                                </div>
							</a>
							<div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2">
								<a class='dropdown-item py-2 px-3 rounded-top-4' href='#'><i class="align-middle me-2 text-primary" data-feather="user"></i> {{ __('Perfil') }}</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item py-2 px-3" href="{{ route('builder.general') }}">{{ __('Configuración') }}</a>
								<div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 px-3 text-danger rounded-bottom-4">
                                        <i class="align-middle me-2" data-feather="log-out"></i> {{ __('Cerrar Sesión') }}
                                    </button>
                                </form>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content pt-3">
				<div class="container-fluid p-0">
                    {{ $slot }}
				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="#" target="_blank"><strong>LogiSaaS</strong></a> &copy; {{ date('Y') }}
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="#" target="_blank">Soporte</a>
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
        // Definitive fix for Feather Icons crash with Livewire
        function initFeather() {
            if (typeof feather !== 'undefined') {
                try {
                    // Pre-filter icons to prevent "toSvg" of undefined error
                    const icons = document.querySelectorAll('[data-feather]');
                    icons.forEach(el => {
                        const name = el.getAttribute('data-feather');

                        // If it's already an SVG, we check if it needs update (rarely needed for static icons)
                        if (el.tagName.toLowerCase() === 'svg') return;

                        // Check if icon exists in the library
                        if (!name || !feather.icons || !feather.icons[name]) {
                            if (name) console.warn(`Feather icon "${name}" not found in current library.`);
                            el.removeAttribute('data-feather');
                            return;
                        }
                    });

                    // Call replace only for elements that still have the attribute
                    feather.replace();
                } catch (e) {
                    console.error('Error initializing Feather icons:', e);
                }
            }
        }

        document.addEventListener('livewire:navigated', initFeather);
        document.addEventListener('DOMContentLoaded', initFeather);

        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                // Settle DOM before replacing icons
                requestAnimationFrame(() => {
                    initFeather();
                });
            });
        });

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', () => {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
</body>

</html>
