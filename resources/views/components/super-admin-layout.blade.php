<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>SuperAdmin Global | LogiSaaS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
	<link href="{{ asset('adminkit/css/light.css') }}" rel="stylesheet">
    <x-brand-styles />

	<style>
        .sidebar-brand-text {
            color: #3b7ddd;
            font-weight: 800;
        }
        .sidebar-brand-text span {
            color: #222e3c;
        }
        [data-theme="default"] .sidebar-brand-text span {
            color: white;
        }
        :root {
            --bs-primary: #3b7ddd;
        }
        .sidebar-badge {
            background: rgba(59, 125, 221, 0.1);
            color: #3b7ddd;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 800;
            margin-left: 5px;
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
				<a class='sidebar-brand d-flex flex-column align-items-center py-4' href='{{ route('super.dashboard') }}'>
                    <div class="mb-2">
                        <i class="align-middle text-primary" data-feather="shield" style="width: 40px; height: 40px;"></i>
                    </div>
                    <span class="sidebar-brand-text align-middle">
                        Logi<span>SaaS</span> <small class="sidebar-badge">CORE</small>
                    </span>
				</a>

                <div class="sidebar-user">
                    <div class="d-flex justify-content-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-md bg-dark text-white rounded me-1 d-flex align-items-center justify-content-center font-bold" style="width: 40px; height: 40px;">
                                SA
                            </div>
                        </div>
                        <div class="flex-grow-1 ps-2">
                            <a class="sidebar-user-title dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-start">
                                <a class='dropdown-item' href='#'><i class="align-middle me-1" data-feather="user"></i> Perfil Global</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Cerrar Sesión</button>
                                </form>
                            </div>
                            <div class="sidebar-user-subtitle">Super Administrador</div>
                        </div>
                    </div>
                </div>

				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Control Global
					</li>

					<li class="sidebar-item {{ request()->routeIs('super.dashboard') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('super.dashboard') }}'>
							<i class="align-middle" data-feather="monitor"></i> <span class="align-middle">Dashboard Global</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('super.tenants*') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('super.tenants') }}'>
							<i class="align-middle" data-feather="layers"></i> <span class="align-middle">Gestión de Tenants</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('super.plans*') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('super.plans') }}'>
							<i class="align-middle" data-feather="shopping-bag"></i> <span class="align-middle">Planes y Precios</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('super.billing') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('super.billing') }}'>
							<i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Facturación SaaS</span>
						</a>
					</li>

                    <li class="sidebar-header">
						Ecosistema
					</li>

                    <li class="sidebar-item {{ request()->routeIs('super.inventory') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('super.inventory') }}'>
							<i class="align-middle" data-feather="package"></i> <span class="align-middle">Inventario Global</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('super.tracking') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('super.tracking') }}'>
							<i class="align-middle" data-feather="search"></i> <span class="align-middle">Buscador Maestro</span>
						</a>
					</li>

                    <li class="sidebar-item">
						<a class='sidebar-link' href='#'>
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Usuarios Globales</span>
						</a>
					</li>

					<li class="sidebar-header">
						Configuración Base
					</li>

                    <li class="sidebar-item {{ request()->routeIs('super.settings') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('super.settings') }}'>
							<i class="align-middle" data-feather="settings"></i> <span class="align-middle">Ajustes del Núcleo</span>
						</a>
					</li>

                    <li class="sidebar-item {{ request()->routeIs('super.api') ? 'active' : '' }}">
						<a class='sidebar-link' href='{{ route('super.api') }}'>
							<i class="align-middle" data-feather="code"></i> <span class="align-middle">API & Webhooks</span>
						</a>
					</li>
				</ul>

				<div class="sidebar-cta">
					<div class="sidebar-cta-content">
						<strong class="d-inline-block mb-2">Master Control</strong>
						<div class="mb-3 text-sm">
							Panel de control centralizado para el operador del SaaS.
						</div>
						<div class="d-grid">
							<a href="{{ route('dashboard') }}" class="btn btn-primary">Ver Panel de Control Operativo</a>
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
                        <li class="nav-item">
                            <span class="badge bg-success-light text-success fw-bold uppercase px-3 py-2">System Online</span>
                        </li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								<div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-dark text-white rounded-circle d-flex align-items-center justify-center font-bold me-2 shadow-sm" style="width: 38px; height: 38px; border: 2px solid white;">
                                        SA
                                    </div>
                                    <div class="text-start d-none d-lg-block">
                                        <div class="text-dark fw-black small leading-none">Root Admin</div>
                                        <div class="text-muted xsmall uppercase font-bold mt-1">Super Global</div>
                                    </div>
                                </div>
							</a>
							<div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2">
								<a class='dropdown-item py-2 px-3 rounded-top-4' href='#'><i class="align-middle me-2 text-primary" data-feather="user"></i> Perfil</a>
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
								<strong>LogiSaaS Core</strong> &copy; {{ date('Y') }}
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="{{ asset('adminkit/js/app.js') }}"></script>
    <script>
        function initFeather() {
            if (typeof feather !== 'undefined') {
                try {
                    const icons = document.querySelectorAll('[data-feather]');
                    icons.forEach(el => {
                        const name = el.getAttribute('data-feather');
                        if (el.tagName.toLowerCase() === 'svg') return;
                        if (!name || !feather.icons[name]) {
                            el.removeAttribute('data-feather');
                        }
                    });
                    feather.replace();
                } catch (e) {}
            }
        }
        document.addEventListener('livewire:navigated', initFeather);
        document.addEventListener('DOMContentLoaded', initFeather);
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                requestAnimationFrame(initFeather);
            });
        });
    </script>
</body>
</html>
