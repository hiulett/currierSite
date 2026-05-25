<div>
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 uppercase font-black tracking-tight">Estrategia de Precios</h1>
            <p class="text-muted">Configure los planes de suscripción y las capacidades de cada nivel.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- New Plan / Edit Form -->
        <div class="col-12 col-xl-4 order-2 order-xl-1">
            <div class="card shadow-sm border-0">
                <div class="card-header {{ $isEditing ? 'bg-warning' : 'bg-primary' }} text-white">
                    <h5 class="card-title text-white mb-0 uppercase font-black small">
                        {{ $isEditing ? 'Editando Plan: ' . $name : 'Configurar Nuevo Plan' }}
                    </h5>
                </div>
                <form wire:submit.prevent="save">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small font-black text-uppercase text-muted">Nombre Comercial</label>
                            <input type="text" wire:model="name" class="form-control border-2 fw-bold" placeholder="Ej: Business Pro">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <label class="form-label small font-black text-uppercase text-muted">Mensual</label>
                                <input type="number" step="0.01" wire:model="price" class="form-control border-2 text-center">
                            </div>
                            <div class="col-4">
                                <label class="form-label small font-black text-uppercase text-muted">Anual</label>
                                <input type="number" step="0.01" wire:model="price_annual" class="form-control border-2 text-center">
                            </div>
                            <div class="col-4">
                                <label class="form-label small font-black text-uppercase text-muted">5 Años</label>
                                <input type="number" step="0.01" wire:model="price_5year" class="form-control border-2 text-center">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small font-black text-uppercase text-muted">Límite Usuarios</label>
                                <input type="number" wire:model="limit_users" class="form-control border-2">
                            </div>
                            <div class="col-6">
                                <label class="form-label small font-black text-uppercase text-muted">Paquetes / Mes</label>
                                <input type="number" wire:model="limit_packages_month" class="form-control border-2">
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border mb-3" style="max-height: 400px; overflow-y: auto;">
                            <label class="form-label small font-black text-uppercase text-primary mb-3 d-block">Módulos Activos</label>

                            @foreach($available_modules as $module)
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" wire:model="selected_features" value="{{ $module }}" id="mod-{{ md5($module) }}">
                                    <label class="form-check-label small fw-bold" for="mod-{{ md5($module) }}">{{ $module }}</label>
                                </div>
                            @endforeach

                            <hr class="my-3">

                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" wire:model="has_website_builder" id="has_website_builder">
                                <label class="form-check-label small fw-bold" for="has_website_builder">Editor de Páginas (Próximamente)</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" wire:model="has_api_access" id="has_api_access">
                                <label class="form-check-label small fw-bold" for="has_api_access">Acceso API / Webhooks</label>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" wire:model="is_featured" id="is_featured">
                                <label class="form-check-label small fw-bold" for="is_featured">Destacado (Recomendado)</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-4">
                        <div class="d-flex gap-2">
                            @if($isEditing)
                                <button type="button" wire:click="cancelEdit" class="btn btn-light border flex-fill fw-bold">CANCELAR</button>
                            @endif
                            <button type="submit" class="btn btn-primary flex-fill py-2 fw-black text-uppercase shadow-sm">
                                <i class="align-middle me-2" data-feather="save"></i>
                                {{ $isEditing ? 'ACTUALIZAR' : 'GUARDAR PLAN' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Plans Cards -->
        <div class="col-12 col-xl-8 order-1 order-xl-2 mb-4">
            <div class="row mb-4 align-items-center">
                <div class="col-auto">
                    <span class="small text-muted text-uppercase fw-black">Ordenar por:</span>
                </div>
                <div class="col-auto">
                    <div class="btn-group btn-group-sm">
                        <button wire:click="sortBy('name')" class="btn btn-light border {{ $sortField === 'name' ? 'active fw-black' : '' }}">Nombre</button>
                        <button wire:click="sortBy('price')" class="btn btn-light border {{ $sortField === 'price' ? 'active fw-black' : '' }}">Precio</button>
                        <button wire:click="sortBy('created_at')" class="btn btn-light border {{ $sortField === 'created_at' ? 'active fw-black' : '' }}">Recientes</button>
                    </div>
                </div>
                <div class="col text-end">
                    <span class="text-muted small uppercase font-bold">{{ $plans->total() }} planes disponibles</span>
                </div>
            </div>

            <div class="row">
                @foreach($plans as $plan)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 overflow-hidden {{ $plan->is_featured ? 'border-top border-primary border-4' : '' }} {{ !$plan->is_active ? 'opacity-50' : '' }}">
                            @if(!$plan->is_active)
                                <div class="bg-danger text-white text-center small py-1 fw-black uppercase">EN BAJA (INACTIVO)</div>
                            @elseif($plan->is_featured)
                                <div class="bg-primary text-white text-center small py-1 fw-black uppercase">RECOMENDADO</div>
                            @endif

                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h4 class="fw-black text-dark mb-0">{{ $plan->name }}</h4>
                                    <span class="badge bg-light text-dark border">{{ $plan->limit_users }} Users</span>
                                </div>

                                <div class="mb-4">
                                    <h2 class="fw-black text-primary mb-0">${{ number_format($plan->price, 2) }} <small class="text-muted small fw-normal">/mes</small></h2>
                                    @if($plan->price_annual)
                                        <div class="small text-success fw-bold">Anual: ${{ number_format($plan->price_annual, 2) }}</div>
                                    @endif
                                    @if($plan->price_5year)
                                        <div class="small text-info fw-bold">5 Años: ${{ number_format($plan->price_5year, 2) }}</div>
                                    @endif
                                </div>

                                <ul class="list-unstyled mb-4">
                                    @if($plan->features_json)
                                        @foreach($plan->features_json as $feature)
                                            <li class="mb-2 small fw-bold text-muted d-flex align-items-start">
                                                <i class="text-success me-2 mt-1" data-feather="check-circle" style="width: 14px; height: 14px;"></i>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="mb-2 small fw-bold text-muted">
                                            <i class="text-success me-2" data-feather="check-circle" style="width: 14px;"></i>
                                            {{ number_format($plan->limit_packages_month) }} paquetes/mes
                                        </li>
                                    @endif
                                </ul>

                                <div class="btn-group w-100">
                                    <button wire:click="edit({{ $plan->id }})" class="btn btn-outline-primary btn-sm fw-bold">EDITAR</button>
                                    <button wire:click="toggleStatus({{ $plan->id }})" class="btn btn-outline-{{ $plan->is_active ? 'danger' : 'success' }} btn-sm fw-bold">
                                        {{ $plan->is_active ? 'BAJA' : 'ALTA' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $plans->links() }}
            </div>
        </div>
    </div>
</div>
