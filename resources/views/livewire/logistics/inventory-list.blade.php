<div class="container-fluid p-0" x-data="{ isAssigning: @entangle('is_assigning') }">
    <!-- Header & Tabs -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Centro de Inventario</h1>
            <p class="text-muted small">Gestiona, asigna y rastrea toda la carga recibida.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group shadow-sm">
                <button wire:click="$set('view_tab', 'all')" class="btn {{ $view_tab === 'all' ? 'btn-primary' : 'btn-white' }} fw-bold">Todos</button>
                <button wire:click="$set('view_tab', 'pending')" class="btn {{ $view_tab === 'pending' ? 'btn-primary' : 'btn-white' }} fw-bold">
                    Sin Asignar <span class="badge bg-danger ms-1">{{ $stats['pending_assignment'] }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats Summary -->
    <div class="row mb-4">
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stat bg-primary-light text-primary me-3">
                        <i data-feather="box"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-black text-dark">{{ number_format($stats['total_count']) }}</h4>
                        <p class="text-muted xsmall uppercase font-bold mb-0">Total Stock</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stat bg-info-light text-info me-3">
                        <i data-feather="database"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-black text-dark">{{ number_format($stats['total_weight'], 1) }} lbs</h4>
                        <p class="text-muted xsmall uppercase font-bold mb-0">Peso Acumulado</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4 border-0" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i data-feather="search" style="width: 14px;"></i></span>
                        <input type="text" wire:model.live="search" class="form-control border-0 bg-light" placeholder="Buscar tracking, descripción o PTY...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filter_warehouse" class="form-select border-0 bg-light">
                        <option value="">Bodegas: Todas</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filter_status" class="form-select border-0 bg-light">
                        <option value="">Estados: Todos</option>
                        <option value="received">Recibido</option>
                        <option value="arrived">En País</option>
                        <option value="ready_for_pickup">Listo Entrega</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i data-feather="calendar" style="width: 14px;"></i></span>
                        <input type="date" wire:model.live="filter_date" class="form-control border-0 bg-light px-0">
                    </div>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="view_tab" class="form-select border-0 bg-light">
                        <option value="all">Filtro: Todos</option>
                        <option value="pending">Solo Sin Asignar</option>
                        <option value="assigned">Solo Asignados</option>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <button onclick="openReceiveModal()" class="btn btn-primary fw-black text-uppercase px-4 shadow-sm">
                        <i class="align-middle me-1" data-feather="plus"></i> NUEVO INGRESO
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card border-0 shadow-sm position-relative overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 40px;" class="ps-4">
                            <input type="checkbox" class="form-check-input" wire:model.live="selectAll">
                        </th>
                        <th class="cursor-pointer" wire:click="sortBy('tracking_number')">Tracking / Info</th>
                        <th>Cliente</th>
                        <th>Ubicación</th>
                        <th class="cursor-pointer" wire:click="sortBy('weight')">Peso</th>
                        <th>Estado</th>
                        <th class="pe-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr class="{{ in_array($package->id, $selected_packages) ? 'bg-primary bg-opacity-5' : '' }}">
                            <td class="ps-4">
                                <input type="checkbox" class="form-check-input" value="{{ $package->id }}" wire:model.live="selected_packages">
                            </td>
                            <td>
                                <div class="fw-black text-dark">{{ $package->tracking_number }}</div>
                                <div class="text-muted xsmall">{{ Str::limit($package->description ?: 'Sin descripción', 40) }}</div>
                            </td>
                            <td>
                                @if($package->customer && $package->customer->user)
                                    <div class="fw-bold leading-none text-dark">{{ $package->customer->user->name }}</div>
                                    <div class="text-primary small font-black uppercase">{{ $package->customer->box_number }}</div>
                                @elseif($package->customer)
                                    <div class="text-danger small fw-bold">Usuario no vinculado</div>
                                    <div class="text-primary small font-black uppercase">{{ $package->customer->box_number }}</div>
                                @else
                                    <span class="badge bg-warning-light text-warning fw-bold text-uppercase" style="font-size: 0.6rem;">PENDIENTE ASIGNAR</span>
                                @endif
                            </td>
                            <td>
                                <div class="small fw-bold text-dark">{{ $package->warehouse->code ?? 'N/A' }}</div>
                                @if($package->shelf_location)
                                    <div class="badge bg-light text-dark border xsmall"><i data-feather="map-pin" class="me-1" style="width: 10px;"></i> {{ $package->shelf_location }}</div>
                                @endif
                            </td>
                            <td><span class="fw-black">{{ $package->weight }} lbs</span></td>
                            <td>
                                <span class="badge text-uppercase" style="font-size: 0.6rem; background-color: {{ $package->getStatusColor() }}">
                                    {{ $package->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group shadow-none">
                                    @if(!$package->customer_id)
                                        <button wire:click="editPackage({{ $package->id }})" class="btn btn-sm btn-primary fw-black px-3">
                                            ASIGNAR
                                        </button>
                                    @else
                                        <button wire:click="unassignPackage({{ $package->id }})" wire:confirm="¿Seguro que deseas quitar este paquete del cliente? El inventario volverá a estar libre." class="btn btn-sm btn-outline-danger" title="Desasociar Cliente">
                                            <i class="align-middle" data-feather="user-minus" style="width: 14px;"></i>
                                        </button>
                                    @endif
                                    <button wire:click="editPackage({{ $package->id }})" class="btn btn-sm btn-light border shadow-none" title="Editar">
                                        <i class="align-middle" data-feather="edit-2" style="width: 14px;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted italic">No se encontraron paquetes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top">
            {{ $packages->links() }}
        </div>
    </div>

    <!-- Floating Action Bar for Bulk Assignment -->
    @if(!empty($selected_packages))
        <div class="position-fixed bottom-0 start-50 translate-middle-x mb-4 z-3" style="width: auto;">
            <div class="bg-dark text-white rounded-pill px-4 py-3 shadow-lg d-flex align-items-center gap-4 border border-white border-opacity-10">
                <div class="fw-black uppercase small tracking-widest">
                    {{ count($selected_packages) }} Paquetes Seleccionados
                </div>
                <div class="vr"></div>
                <button wire:click="openAssignment" class="btn btn-primary btn-sm rounded-pill fw-black px-4">
                    ASIGNAR <i class="align-middle ms-1" data-feather="users"></i>
                </button>
                <button wire:click="bulkUnassign" wire:confirm="¿Deseas desasociar todos los paquetes seleccionados?" class="btn btn-outline-danger btn-sm rounded-pill fw-black px-4">
                    QUITAR CLIENTE <i class="align-middle ms-1" data-feather="user-minus"></i>
                </button>
                <button wire:click="$set('selected_packages', [])" class="btn btn-link text-white-50 btn-sm p-0 text-decoration-none fw-bold">Cancelar</button>
            </div>
        </div>
    @endif

    <!-- SLIDE-OVER PANEL FOR ASSIGNMENT (Inline Simulation) -->
    <div x-show="isAssigning"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="position-fixed top-0 end-0 h-100 bg-white shadow-lg z-3 border-start"
         style="width: 450px; display: none; z-index: 1060;">

        <div class="h-100 d-flex flex-column">
            <div class="p-4 bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="modal-title uppercase font-black tracking-widest text-white mb-0">Asignación Inteligente</h5>
                    <button @click="isAssigning = false" wire:click="cancelAssignment" class="btn-close btn-close-white"></button>
                </div>
                <p class="mb-0 small opacity-75">Vincula los paquetes seleccionados a un cliente y genera su factura automáticamente.</p>
            </div>

            <div class="flex-grow-1 overflow-y-auto p-4">
                <!-- Customer Search -->
                <div class="mb-4">
                    <label class="form-label small font-black text-uppercase text-muted tracking-tighter">1. Seleccionar Cliente</label>
                    <div class="position-relative">
                        <input type="text" wire:model.live="customer_search" class="form-control form-control-lg fw-bold border-2" placeholder="Buscar por Nombre o PTY...">
                        @if(!empty($customer_results))
                            <div class="position-absolute w-100 bg-white shadow-lg border rounded-3 mt-1 z-3">
                                @foreach($customer_results as $result)
                                    <button wire:click="selectCustomer({{ $result->id }})" class="w-100 text-start p-3 border-bottom btn btn-white hover:bg-light rounded-0">
                                        <div class="fw-black text-dark">{{ $result->user->name }}</div>
                                        <div class="small text-primary font-bold">{{ $result->box_number }}</div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @if($selected_customer)
                    <div class="p-3 bg-light rounded-3 border mb-4 animate__animated animate__fadeIn">
                        <div class="d-flex align-items-center">
                            <div class="stat bg-primary text-white rounded-circle me-3">{{ substr($selected_customer->user->name, 0, 1) }}</div>
                            <div>
                                <h6 class="mb-0 fw-black">{{ $selected_customer->user->name }}</h6>
                                <span class="badge bg-primary-light text-primary xsmall fw-black">{{ $selected_customer->box_number }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Assignment Details -->
                <div class="mb-4">
                    <label class="form-label small font-black text-uppercase text-muted tracking-tighter">2. Ubicación en Bodega</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i data-feather="map-pin" style="width: 14px;"></i></span>
                        <input type="text" wire:model="shelf_location" class="form-control" placeholder="Ejem: Pasillo A, Estante 12">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small font-black text-uppercase text-muted tracking-tighter">3. Configuración de Cobro</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="xsmall text-muted mb-1">Tarifa por Libra ($)</label>
                            <input type="number" step="0.01" wire:model="custom_rate" class="form-control fw-bold">
                        </div>
                        <div class="col-6">
                            <label class="xsmall text-muted mb-1">Cargo Extra ($)</label>
                            <input type="number" step="0.01" wire:model="extra_charge" class="form-control fw-bold">
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="text" wire:model="extra_charge_reason" class="form-control form-control-sm" placeholder="Motivo del cargo extra (Ejem: Manejo Frágil)">
                    </div>
                </div>

                @if(session()->has('assign_error'))
                    <div class="alert alert-danger small p-2 border-0 shadow-sm">{{ session('assign_error') }}</div>
                @endif
            </div>

            <div class="p-4 bg-light border-top">
                <button wire:click="confirmAssignment" wire:loading.attr="disabled" class="btn btn-primary btn-lg w-100 fw-black uppercase py-3 shadow-lg">
                    <span wire:loading.remove>CONFIRMAR Y FACTURAR</span>
                    <span wire:loading><span class="spinner-border spinner-border-sm me-2"></span>PROCESANDO...</span>
                </button>
                <button @click="isAssigning = false" wire:click="cancelAssignment" class="btn btn-link w-100 text-muted small mt-2 text-decoration-none">Cancelar Operación</button>
            </div>
        </div>
    </div>

    <!-- Background Overlay for Slide-over -->
    <div x-show="isAssigning"
         @click="isAssigning = false"
         x-transition:opacity
         class="position-fixed top-0 start-0 w-100 h-100 bg-dark opacity-50 z-2"
         style="display: none; z-index: 1055;"></div>

    <!-- Modals (Kept from existing functionality) -->
    <div class="modal fade" id="modalReceivePackage" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem; overflow: hidden; background: #f8fafc;">
                <div class="modal-header border-0 p-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle text-white flex-shrink-0" 
                             style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15);">
                            <i data-feather="zap" style="width: 18px; height: 18px; color: #60a5fa;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-black uppercase text-white mb-0" style="letter-spacing: .05em; font-size: 1.1rem;">Nuevo Ingreso de Carga</h5>
                            <p class="text-white-50 small mb-0 font-bold" style="font-size: 0.75rem;">Centro de Recepción Inteligente</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.8; filter: invert(1);"></button>
                </div>
                <div class="modal-body p-4" style="background: #f8fafc;">
                    @livewire('logistics.smart-reception-hub', ['isModal' => true])
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Package -->
    <div class="modal fade" id="modalEditPackage" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header bg-dark text-white p-4">
                    <h5 class="modal-title uppercase font-black small tracking-widest text-white mb-0">
                        <i class="align-middle me-2" data-feather="edit-3"></i> Editar Paquete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="updatePackage">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small font-black text-uppercase text-muted">Número de Tracking</label>
                            <input type="text" wire:model="edit_tracking_number" class="form-control fw-bold border-2">
                            @error('edit_tracking_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small font-black text-uppercase text-muted">Peso (lbs)</label>
                                <input type="number" step="0.01" wire:model="edit_weight" class="form-control fw-bold border-2">
                                @error('edit_weight') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label small font-black text-uppercase text-muted">Bodega</label>
                                <select wire:model="edit_warehouse_id" class="form-select fw-bold border-2">
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->code }} - {{ $wh->name }}</option>
                                    @endforeach
                                </select>
                                @error('edit_warehouse_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small font-black text-uppercase text-muted">Estado Actual</label>
                            <select wire:model="edit_status" class="form-select fw-bold border-2">
                                <option value="received">Recibido</option>
                                <option value="prealert">Pre-alerta</option>
                                <option value="in_transit">En Tránsito</option>
                                <option value="arrived">Llegó al País</option>
                                <option value="ready_for_pickup">Listo para Entrega</option>
                                <option value="out_for_delivery">En Ruta de Entrega</option>
                                <option value="delivered">Entregado</option>
                            </select>
                            @error('edit_status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label small font-black text-uppercase text-muted">Descripción / Contenido</label>
                            <textarea wire:model="edit_description" rows="2" class="form-control border-2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-primary fw-black px-4">GUARDAR CAMBIOS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReceiveModal() {
            var el = document.getElementById('modalReceivePackage');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        }

        window.addEventListener('open-edit-modal', event => {
            var el = document.getElementById('modalEditPackage');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.show();
        });

        window.addEventListener('close-edit-modal', event => {
            var el = document.getElementById('modalEditPackage');
            var myModal = bootstrap.Modal.getOrCreateInstance(el);
            myModal.hide();
        });
    </script>
</div>
