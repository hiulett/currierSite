<div class="container-fluid p-0">
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h4 mb-0 uppercase font-black tracking-tight text-dark">Personalización de Estados</h1>
            <p class="text-muted xsmall mb-0">Define cómo tus clientes ven el progreso de su carga.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0 uppercase font-black small">Ciclo de Vida del Paquete</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Código Interno</th>
                                <th>Nombre Visual (Label)</th>
                                <th class="text-center">Color</th>
                                <th class="text-center">Icono</th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statuses as $status)
                                <tr>
                                    <td class="ps-4">
                                        <code class="bg-light px-2 py-1 rounded small">{{ $status['name'] }}</code>
                                    </td>
                                    <td>
                                        <div class="fw-black text-dark">{{ $status['label'] }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge" style="background-color: {{ $status['color'] }}; width: 24px; height: 24px; display: inline-block; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.1);"></span>
                                    </td>
                                    <td class="text-center">
                                        <i data-feather="{{ $status['icon'] }}" class="text-muted" style="width: 16px;"></i>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <button wire:click="edit({{ $status['id'] }})" class="btn btn-sm btn-light border shadow-sm">
                                            <i data-feather="edit-2" class="align-middle" style="width: 14px;"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            @if($editing_id)
                <div class="card border-0 shadow-lg animate-in slide-in-from-right-2">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title text-white mb-0 uppercase font-black small">Editar Estado: {{ $name }}</h5>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label small font-black text-uppercase text-muted">Etiqueta para el Cliente</label>
                                <input type="text" wire:model="label" class="form-control fw-bold border-2">
                                @error('label') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small font-black text-uppercase text-muted">Color</label>
                                    <input type="color" wire:model="color" class="form-control form-control-color w-100 border-2" style="height: 45px;">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small font-black text-uppercase text-muted">Orden</label>
                                    <input type="number" wire:model="sort_order" class="form-control fw-bold border-2">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small font-black text-uppercase text-muted">Icono (Feather Name)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-2 border-end-0"><i data-feather="{{ $icon }}"></i></span>
                                    <input type="text" wire:model="icon" class="form-control fw-bold border-2 border-start-0" placeholder="package, truck, flag...">
                                </div>
                                <div class="form-text xsmall italic">Usa nombres de <a href="https://feathericons.com/" target="_blank">FeatherIcons</a>.</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary fw-black py-2 shadow">ACTUALIZAR CAMBIOS</button>
                                <button type="button" wire:click="cancel" class="btn btn-light border fw-bold py-2">CANCELAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="card border-0 bg-dark text-white shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="avatar avatar-lg bg-white bg-opacity-10 text-white rounded-circle d-flex align-items-center justify-center mx-auto mb-3">
                            <i data-feather="settings"></i>
                        </div>
                        <h6 class="text-uppercase font-black small tracking-widest text-white-50">Configurador White-Label</h6>
                        <p class="small mb-0">Selecciona un estado de la lista para cambiar su nombre comercial o color de visualización.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
