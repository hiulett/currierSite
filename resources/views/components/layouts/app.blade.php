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

        /* Feature Toggles (Hide vs Disable) */
        .sidebar-item.disabled-feature {
            opacity: 0.55;
        }
        .sidebar-item.disabled-feature .sidebar-link {
            cursor: not-allowed !important;
            background: transparent !important;
        }
        .sidebar-item.disabled-feature .sidebar-link:hover {
            color: #adb5bd !important;
        }

        /* SubmenÃºs agrupados del sidebar */
        .sidebar-nav-group > .sidebar-link { cursor: pointer; white-space: nowrap; }
        .sidebar-nav-group > .sidebar-link:after {
            content: "";
            width: 7px;
            height: 7px;
            border-right: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: rotate(45deg);
            margin-left: auto;
            opacity: .55;
            transition: transform .2s ease;
        }
        .sidebar-nav-group > .sidebar-link[aria-expanded="true"]:after {
            transform: rotate(225deg);
            opacity: .9;
        }
        .sidebar-nav-group.active > .sidebar-link { color: #fff; }
        body[data-theme="light"] .sidebar-nav-group.active > .sidebar-link { color: #3b7ddd; }
        .sidebar-dropdown > .sidebar-nav { padding: .25rem 0 .5rem; }
        .sidebar-dropdown .sidebar-item .sidebar-link {
            padding-left: 3.5rem;
            font-size: .875rem;
            border-left: 0;
            white-space: nowrap;
        }
        .sidebar-dropdown .sidebar-item .sidebar-link:hover {
            color: #e9ecef;
            background: rgba(59,125,221,.12);
        }
        .sidebar-dropdown .sidebar-item.active > .sidebar-link,
        .sidebar-dropdown .sidebar-item.active .sidebar-link:hover {
            color: #fff;
            background: linear-gradient(90deg,rgba(59,125,221,.1),rgba(59,125,221,.088) 50%,transparent);
            border-left: 0;
        }
        body[data-theme="light"] .sidebar-dropdown .sidebar-item .sidebar-link { color: #6c757d; }
        body[data-theme="light"] .sidebar-dropdown .sidebar-item .sidebar-link:hover { color: #518be1; }
        body[data-theme="light"] .sidebar-dropdown .sidebar-item.active > .sidebar-link { color: #518be1; }
	</style>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
    @if(session()->has('impersonate_tenant_id'))
        <div class="bg-warning text-dark py-1 px-4 text-center fw-bold small uppercase tracking-tighter shadow-sm d-flex align-items-center justify-content-center" style="font-size: 0.75rem; min-height: 35px;">
            <i data-feather="eye" class="me-2" style="width: 14px; height: 14px;"></i>
            <span>Modo VisualizaciÃ³n: EstÃ¡s viendo el panel de <strong>{{ config('app.name') }}</strong></span>
            <a href="{{ route('super.stop-impersonating') }}" class="btn btn-dark btn-sm ms-3 py-0 px-2 fw-black" style="font-size: 0.65rem; height: 22px; line-height: 22px;">
                SALIR MODO DIOS
            </a>
        </div>
    @endif

    @if($showBillingAlert ?? false)
        <div class="bg-danger text-white py-2 px-4 text-center fw-bold shadow-sm d-flex align-items-center justify-content-center" style="font-size: 0.85rem; border-bottom: 2px solid rgba(0,0,0,0.1);">
            <i data-feather="alert-triangle" class="me-2" style="width: 18px; height: 18px;"></i>
            <span>AVISO IMPORTANTE: Su periodo de suscripciÃ³n ha vencido. Por favor, reporte su pago para evitar interrupciones en el servicio.</span>
            @if(Auth::user()->role === 'admin')
                <a href="#" class="btn btn-light btn-sm ms-3 py-0 px-3 fw-black text-danger" style="font-size: 0.7rem; height: 26px; line-height: 26px;">
                    REPORTE AQUÃ
                </a>
            @endif
        </div>
    @endif

    @php
        $navAlerts = $navAlerts ?? [];
        $totalNavAlerts = $totalNavAlerts ?? 0;
    @endphp
	<div class="wrapper">
        @livewire('logistics.global-search')

		@include('components.layouts.partials.sidebar')

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg floating-navbar shadow-sm mx-4 mt-2">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item d-none d-md-block">
							<a class="nav-icon" href="#" onclick="event.preventDefault(); Livewire.dispatch('global-search-toggle');" title="BÃºsqueda rÃ¡pida (Ctrl+K)" aria-label="BÃºsqueda rÃ¡pida">
								<i class="align-middle" data-feather="search"></i>
							</a>
						</li>
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
								<a class="dropdown-item py-2 px-3" href="{{ route('builder.general') }}">{{ __('ConfiguraciÃ³n') }}</a>
								<div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 px-3 text-danger rounded-bottom-4">
                                        <i class="align-middle me-2" data-feather="log-out"></i> {{ __('Cerrar SesiÃ³n') }}
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
								<strong>{{ $tenant->name ?? config('app.name') }}</strong> &copy; {{ date('Y') }}
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

	<script src="{{ asset('adminkit/js/app.js') }}" data-navigate-once></script>
    <script data-navigate-once>
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

        // Global search shortcut (Ctrl/Cmd + K)
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('global-search-toggle');
                }
            }
        });

        // Locked feature interactive notice
        function showLockedFeatureModal(featureName) {
            const toastHtml = `
                <div class="toast show align-items-center text-white bg-dark border-0 shadow-lg position-fixed bottom-0 end-0 m-3 animate-in fade-in zoom-in duration-300" style="z-index: 1200; min-width: 320px;" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body fw-bold d-flex align-items-center py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock text-warning me-3" style="width: 20px; height: 20px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <div>
                                El mÃ³dulo de <span class="text-warning">${featureName}</span> estÃ¡ bloqueado en su plan. Contacte al administrador.
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            const container = document.createElement('div');
            container.innerHTML = toastHtml;
            document.body.appendChild(container.firstElementChild);
            
            // Auto close toast after 5 seconds
            const lastToast = document.body.lastElementChild;
            setTimeout(() => {
                if (lastToast && lastToast.parentNode) {
                    lastToast.remove();
                }
            }, 5000);
        }
    </script>

    <!-- Bootstrap Toast Container for Warning/Success/Error Alerts -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1250">
        @if(session()->has('warning'))
            <div class="toast show align-items-center text-white bg-warning border-0 shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
                <div class="d-flex">
                    <div class="toast-body fw-bold d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle me-2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        {{ session('warning') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session()->has('success'))
            <div class="toast show align-items-center text-white bg-success border-0 shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
                <div class="d-flex">
                    <div class="toast-body fw-bold d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle me-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="toast show align-items-center text-white bg-danger border-0 shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
                <div class="d-flex">
                    <div class="toast-body fw-bold d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle me-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>
    <script data-navigate-once>
        // Persistir scroll del sidebar entre navegaciones
        (function () {
            var KEY = 'logysaas.sidebar.scroll';
            function scroller() {
                return document.querySelector('.sidebar-content') || document.getElementById('sidebar');
            }
            window.addEventListener('beforeunload', function () {
                var el = scroller();
                if (el) { try { sessionStorage.setItem(KEY, String(el.scrollTop)); } catch (e) {} }
            });
            function restoreSidebarScroll() {
                var el = scroller();
                var v = null;
                try { v = sessionStorage.getItem(KEY); } catch (e) {}
                if (el && v) { el.scrollTop = parseInt(v, 10) || 0; }
            }
            window.addEventListener('DOMContentLoaded', restoreSidebarScroll);
            document.addEventListener('livewire:navigated', restoreSidebarScroll);
        })();
    </script>
</body>
</html>
