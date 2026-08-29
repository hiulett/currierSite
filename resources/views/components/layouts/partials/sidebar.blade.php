		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class='sidebar-brand d-flex flex-column align-items-center py-4' wire:navigate href='{{ route('dashboard') }}'>
                    @php
                        $tenant = \App\Models\Tenant::current() ?? \App\Models\Tenant::first();
                        $logoUrl = $tenant?->getLogoUrl();
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
                </div				<ul id="sidebar-nav" class="sidebar-nav">
					<li class="sidebar-header">
						{{ __('Principal') }}
					</li>

					@tenantFeatureNotHidden('dashboard')
					<li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} @tenantFeatureDisabled('dashboard') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('dashboard')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Dashboard')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">{{ __('Dashboard') }}</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('dashboard') }}'>
							<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">{{ __('Dashboard') }}</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

                    <!-- PROCESO 1: ENTRADA -->
@php $entradaActive = request()->routeIs('logistics.smart-reception') || request()->routeIs('logistics.receive-manifest'); @endphp
<li class="sidebar-item sidebar-nav-group" x-data="{ open: {{ $entradaActive ? 'true' : 'false' }} }" :class="open ? 'active' : ''">
    <a class="sidebar-link" @click.prevent="open = !open" :aria-expanded="open ? 'true' : 'false'" href="#collapseEntrada">
        <i class="align-middle" data-feather="log-in"></i> <span class="align-middle">Entrada de Carga</span>
    </a>
    <div class="sidebar-dropdown" x-show="open" id="collapseEntrada">
        <ul class="sidebar-nav">


					@can('logistics.receive')
					@tenantFeatureNotHidden('recepcion_paquetes')
                    <li class="sidebar-item {{ request()->routeIs('logistics.smart-reception') ? 'active' : '' }} @tenantFeatureDisabled('recepcion_paquetes') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('recepcion_paquetes')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Recepción de Paquetes')">
							<i class="align-middle text-primary" data-feather="lock"></i> <span class="align-middle">Recepción de Paquetes</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.smart-reception') }}'>
							<i class="align-middle text-primary" data-feather="zap"></i> <span class="align-middle">Recepción de Paquetes</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('control_manifiestos')
                    <li class="sidebar-item {{ request()->routeIs('logistics.receive-manifest') ? 'active' : '' }} @tenantFeatureDisabled('control_manifiestos') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('control_manifiestos')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Control Manifiestos')">
							<i class="align-middle text-warning" data-feather="lock"></i> <span class="align-middle">Control Manifiestos</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.receive-manifest') }}'>
							<i class="align-middle text-warning" data-feather="file-text"></i> <span class="align-middle">Control Manifiestos</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan</ul>
    </div>
</li>


                    <!-- PROCESO 2: ALMACENAMIENTO -->
@php $bodegaActive = request()->routeIs('logistics.inventory') || request()->routeIs('logistics.tracking') || request()->routeIs('logistics.repack'); @endphp
<li class="sidebar-item sidebar-nav-group" x-data="{ open: {{ $bodegaActive ? 'true' : 'false' }} }" :class="open ? 'active' : ''">
    <a class="sidebar-link" @click.prevent="open = !open" :aria-expanded="open ? 'true' : 'false'" href="#collapseBodega">
        <i class="align-middle" data-feather="archive"></i> <span class="align-middle">Gestión de Bodega</span>
    </a>
    <div class="sidebar-dropdown" x-show="open" id="collapseBodega">
        <ul class="sidebar-nav">


					@can('logistics.inventory')
					@tenantFeatureNotHidden('inventario_activo')
					<li class="sidebar-item {{ request()->routeIs('logistics.inventory') ? 'active' : '' }} @tenantFeatureDisabled('inventario_activo') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('inventario_activo')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Inventario Activo')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Inventario Activo</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.inventory') }}'>
							<i class="align-middle" data-feather="box"></i> <span class="align-middle">Inventario Activo</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('rastreo_global')
                    <li class="sidebar-item {{ request()->routeIs('logistics.tracking') ? 'active' : '' }} @tenantFeatureDisabled('rastreo_global') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('rastreo_global')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Rastreo Global')">
							<i class="align-middle text-info" data-feather="lock"></i> <span class="align-middle">Rastreo Global</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.tracking') }}'>
							<i class="align-middle text-info" data-feather="search"></i> <span class="align-middle">Rastreo Global</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

					@can('logistics.repack')
					@tenantFeatureNotHidden('reempaque_consolidacion')
					<li class="sidebar-item {{ request()->routeIs('logistics.repack') ? 'active' : '' }} @tenantFeatureDisabled('reempaque_consolidacion') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('reempaque_consolidacion')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Reempaque / Consolidación')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Reempaque / Consolidación</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.repack') }}'>
							<i class="align-middle" data-feather="package"></i> <span class="align-middle">Reempaque / Consolidación</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan</ul>
    </div>
</li>


                    <!-- PROCESO 3: SALIDAS Y ENTREGAS -->
@php $salidasActive = request()->routeIs('logistics.shipments.*') || request()->routeIs('logistics.delivery') || request()->routeIs('logistics.counter'); @endphp
<li class="sidebar-item sidebar-nav-group" x-data="{ open: {{ $salidasActive ? 'true' : 'false' }} }" :class="open ? 'active' : ''">
    <a class="sidebar-link" @click.prevent="open = !open" :aria-expanded="open ? 'true' : 'false'" href="#collapseSalidas">
        <i class="align-middle" data-feather="truck"></i> <span class="align-middle">Salidas y Entrega</span>
    </a>
    <div class="sidebar-dropdown" x-show="open" id="collapseSalidas">
        <ul class="sidebar-nav">


					@can('logistics.shipments')
					@tenantFeatureNotHidden('embarques')
					<li class="sidebar-item {{ request()->routeIs('logistics.shipments.*') ? 'active' : '' }} @tenantFeatureDisabled('embarques') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('embarques')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Embarques (Outbound)')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Embarques (Outbound)</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.shipments.index') }}'>
							<i class="align-middle" data-feather="truck"></i> <span class="align-middle">Embarques (Outbound)</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

					@can('logistics.delivery')
					@tenantFeatureNotHidden('ultima_milla')
					<li class="sidebar-item {{ request()->routeIs('logistics.delivery') ? 'active' : '' }} @tenantFeatureDisabled('ultima_milla') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('ultima_milla')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Última Milla (Delivery)')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Última Milla (Delivery)</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.delivery') }}'>
							<i class="align-middle" data-feather="map-pin"></i> <span class="align-middle">Última Milla (Delivery)</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

                    @can('logistics.counter')
					@tenantFeatureNotHidden('entrega_counter')
                    <li class="sidebar-item {{ request()->routeIs('logistics.counter') ? 'active' : '' }} @tenantFeatureDisabled('entrega_counter') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('entrega_counter')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Entrega en Counter')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Entrega en Counter</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.counter') }}'>
							<i class="align-middle" data-feather="box"></i> <span class="align-middle">Entrega en Counter</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan</ul>
    </div>
</li>


                    <!-- ADMINISTRACIÓN -->
@php $clientesActive = request()->routeIs('logistics.customers*') || request()->routeIs('logistics.tickets') || request()->routeIs('logistics.lockers'); @endphp
<li class="sidebar-item sidebar-nav-group" x-data="{ open: {{ $clientesActive ? 'true' : 'false' }} }" :class="open ? 'active' : ''">
    <a class="sidebar-link" @click.prevent="open = !open" :aria-expanded="open ? 'true' : 'false'" href="#collapseClientes">
        <i class="align-middle" data-feather="users"></i> <span class="align-middle">Relaciones y Soporte</span>
    </a>
    <div class="sidebar-dropdown" x-show="open" id="collapseClientes">
        <ul class="sidebar-nav">


                    @can('customers.view')
					@tenantFeatureNotHidden('base_clientes')
                    <li class="sidebar-item {{ request()->routeIs('logistics.customers') ? 'active' : '' }} @tenantFeatureDisabled('base_clientes') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('base_clientes')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Base de Clientes')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Base de Clientes</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.customers') }}'>
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Base de Clientes</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

                    @can('tickets.manage')
					@tenantFeatureNotHidden('soporte_tickets')
                    <li class="sidebar-item {{ request()->routeIs('logistics.tickets') ? 'active' : '' }} @tenantFeatureDisabled('soporte_tickets') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('soporte_tickets')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Soporte (Tickets)')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Soporte (Tickets)</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.tickets') }}'>
							<i class="align-middle" data-feather="message-square"></i> <span class="align-middle">Soporte (Tickets)</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

                    @can('logistics.inventory')
					@tenantFeatureNotHidden('casilleros_fisicos')
                    <li class="sidebar-item {{ request()->routeIs('logistics.lockers') ? 'active' : '' }} @tenantFeatureDisabled('casilleros_fisicos') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('casilleros_fisicos')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Casilleros Físicos')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Casilleros Físicos</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.lockers') }}'>
							<i class="align-middle" data-feather="grid"></i> <span class="align-middle">Casilleros Físicos</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan</ul>
    </div>
</li>


                    <!-- FINANZAS -->
@php $finanzasActive = request()->routeIs('billing.*') || request()->routeIs('logistics.reports'); @endphp
<li class="sidebar-item sidebar-nav-group" x-data="{ open: {{ $finanzasActive ? 'true' : 'false' }} }" :class="open ? 'active' : ''">
    <a class="sidebar-link" @click.prevent="open = !open" :aria-expanded="open ? 'true' : 'false'" href="#collapseFinanzas">
        <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Administración Financiera</span>
    </a>
    <div class="sidebar-dropdown" x-show="open" id="collapseFinanzas">
        <ul class="sidebar-nav">


					@can('billing.view')
					@tenantFeatureNotHidden('facturacion')
					<li class="sidebar-item {{ request()->routeIs('billing.index') ? 'active' : '' }} @tenantFeatureDisabled('facturacion') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('facturacion')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Facturación')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Facturación</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('billing.index') }}'>
							<i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Facturación</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('cotizaciones')
                    <li class="sidebar-item {{ request()->routeIs('billing.quotations.index') ? 'active' : '' }} @tenantFeatureDisabled('cotizaciones') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('cotizaciones')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Cotizaciones')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Cotizaciones</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('billing.quotations.index') }}'>
							<i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Cotizaciones</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('control_fletes')
                    <li class="sidebar-item {{ request()->routeIs('billing.driver-trips.index') ? 'active' : '' }} @tenantFeatureDisabled('control_fletes') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('control_fletes')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Control de Fletes')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Control de Fletes</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('billing.driver-trips.index') }}'>
							<i class="align-middle" data-feather="truck"></i> <span class="align-middle">Control de Fletes</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

                    <li class="sidebar-item {{ request()->routeIs('billing.redemptions') ? 'active' : '' }}">
						<a class='sidebar-link' wire:navigate href='{{ route('billing.redemptions') }}'>
							<i class="align-middle text-warning" data-feather="gift"></i> <span class="align-middle">Canjes LOGYPUNTOS</span>
						</a>
					</li>

					@tenantFeatureNotHidden('estados_cuenta')
                    <li class="sidebar-item {{ request()->routeIs('billing.statement') ? 'active' : '' }} @tenantFeatureDisabled('estados_cuenta') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('estados_cuenta')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Estados de Cuenta')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Estados de Cuenta</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('billing.statement') }}'>
							<i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Estados de Cuenta</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

					@can('billing.view')
					@tenantFeatureNotHidden('expenses')
					<li class="sidebar-item {{ request()->routeIs('billing.expenses.index') ? 'active' : '' }} @tenantFeatureDisabled('expenses') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('expenses')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Egresos de Empresa')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Egresos</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('billing.expenses.index') }}'>
							<i class="align-middle text-danger" data-feather="dollar-sign"></i> <span class="align-middle">Egresos</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

                    @can('logistics.reports')
					@tenantFeatureNotHidden('reportes_negocio')
                    <li class="sidebar-item {{ request()->routeIs('logistics.reports') ? 'active' : '' }} @tenantFeatureDisabled('reportes_negocio') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('reportes_negocio')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Reportes de Negocio')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Reportes de Negocio</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('logistics.reports') }}'>
							<i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Reportes de Negocio</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan</ul>
    </div>
</li>


                    <!-- CONFIGURACIÓN -->
@php $configActive = request()->routeIs('builder.*'); @endphp
<li class="sidebar-item sidebar-nav-group" x-data="{ open: {{ $configActive ? 'true' : 'false' }} }" :class="open ? 'active' : ''">
    <a class="sidebar-link" @click.prevent="open = !open" :aria-expanded="open ? 'true' : 'false'" href="#collapseConfig">
        <i class="align-middle" data-feather="settings"></i> <span class="align-middle">Configuración</span>
    </a>
    <div class="sidebar-dropdown" x-show="open" id="collapseConfig">
        <ul class="sidebar-nav">


                    @can('settings.brand')
					@tenantFeatureNotHidden('identidad_visual')
                    <li class="sidebar-item {{ request()->routeIs('builder.brand') ? 'active' : '' }} @tenantFeatureDisabled('identidad_visual') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('identidad_visual')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Identidad Visual')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Identidad Visual</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.brand') }}'>
							<i class="align-middle" data-feather="layout"></i> <span class="align-middle">Identidad Visual</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

                    @can('settings.general')
					@tenantFeatureNotHidden('gestion_bodegas')
                    <li class="sidebar-item {{ request()->routeIs('builder.warehouses') ? 'active' : '' }} @tenantFeatureDisabled('gestion_bodegas') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('gestion_bodegas')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Gestión de Bodegas')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Gestión de Bodegas</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.warehouses') }}'>
							<i class="align-middle" data-feather="home"></i> <span class="align-middle">Gestión de Bodegas</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('pagos_integraciones')
                    <li class="sidebar-item {{ request()->routeIs('builder.integrations') ? 'active' : '' }} @tenantFeatureDisabled('pagos_integraciones') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('pagos_integraciones')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Pagos e Integraciones')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Pagos e Integraciones</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.integrations') }}'>
							<i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Pagos e Integraciones</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('ajustes_correo')
                    <li class="sidebar-item {{ request()->routeIs('builder.mail') ? 'active' : '' }} @tenantFeatureDisabled('ajustes_correo') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('ajustes_correo')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Ajustes de Correo')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Ajustes de Correo</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.mail') }}'>
							<i class="align-middle" data-feather="mail"></i> <span class="align-middle">Ajustes de Correo</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('estados_carga')
                    <li class="sidebar-item {{ request()->routeIs('builder.statuses') ? 'active' : '' }} @tenantFeatureDisabled('estados_carga') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('estados_carga')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Estados de Carga')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Estados de Carga</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.statuses') }}'>
							<i class="align-middle" data-feather="list"></i> <span class="align-middle">Estados de Carga</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('niveles_cliente')
                    <li class="sidebar-item {{ request()->routeIs('builder.loyalty') ? 'active' : '' }} @tenantFeatureDisabled('niveles_cliente') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('niveles_cliente')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Niveles de Cliente')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Niveles de Cliente</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.loyalty') }}'>
							<i class="align-middle" data-feather="award"></i> <span class="align-middle">Niveles de Cliente</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('niveles_cliente')
                    <li class="sidebar-item {{ request()->routeIs('builder.rewards') ? 'active' : '' }}">
						<a class='sidebar-link' wire:navigate href='{{ route('builder.rewards') }}'>
							<i class="align-middle text-warning" data-feather="gift"></i> <span class="align-middle">Recompensas LOGYPUNTOS</span>
						</a>
					</li>
					@endtenantFeatureNotHidden

					@tenantFeatureNotHidden('promociones')
                    <li class="sidebar-item {{ request()->routeIs('builder.promotions') ? 'active' : '' }} @tenantFeatureDisabled('promociones') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('promociones')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Promociones')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Promociones</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.promotions') }}'>
							<i class="align-middle" data-feather="tag"></i> <span class="align-middle">Promociones</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

					@can('settings.users')
					@tenantFeatureNotHidden('usuarios_roles')
                    <li class="sidebar-item {{ request()->routeIs('builder.users') ? 'active' : '' }} @tenantFeatureDisabled('usuarios_roles') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('usuarios_roles')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Colaboradores')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Colaboradores</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.users') }}'>
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Colaboradores</span>
						</a>
						@endtenantFeatureDisabled
					</li>
                    <li class="sidebar-item {{ request()->routeIs('builder.roles') ? 'active' : '' }} @tenantFeatureDisabled('usuarios_roles') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('usuarios_roles')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Roles y Permisos')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Roles y Permisos</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.roles') }}'>
							<i class="align-middle" data-feather="shield"></i> <span class="align-middle">Roles y Permisos</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan

					@can('settings.general')
					@tenantFeatureNotHidden('ajustes_generales')
                    <li class="sidebar-item {{ request()->routeIs('builder.general') ? 'active' : '' }} @tenantFeatureDisabled('ajustes_generales') disabled-feature @endtenantFeatureDisabled">
						@tenantFeatureDisabled('ajustes_generales')
						<a class='sidebar-link' href='javascript:void(0);' onclick="showLockedFeatureModal('Ajustes Generales')">
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Ajustes Generales</span>
						</a>
						@else
						<a class='sidebar-link' wire:navigate href='{{ route('builder.general') }}'>
							<i class="align-middle" data-feather="settings"></i> <span class="align-middle">Ajustes Generales</span>
						</a>
						@endtenantFeatureDisabled
					</li>
					@endtenantFeatureNotHidden
					@endcan</ul>
    </div>
</li>



                    @if(Auth::user()->role === 'superadmin')
                        <li class="sidebar-header">
                            Root Control
                        </li>
                        <li class="sidebar-item">
                            <a class='sidebar-link' wire:navigate href='{{ route('super.dashboard') }}' style="background: rgba(59, 125, 221, 0.1); color: #3b7ddd;">
                                <i class="align-middle text-primary" data-feather="shield"></i> <span class="align-middle fw-bold">Panel Super Admin</span>
                            </a>
                        </li>
                    @endif
				</ul>

				<div class="sidebar-cta">
					<div class="sidebar-cta-content">
						<strong class="d-inline-block mb-2">{{ $tenant->name ?? config('app.name') }}</strong>
						<div class="mb-3 text-sm">
							Sistema integral de logística.
						</div>
						<div class="d-grid">
							<a href="#" class="btn btn-primary">Documentación</a>
						</div>
					</div>
				</div>
			</div>
		</nav>
