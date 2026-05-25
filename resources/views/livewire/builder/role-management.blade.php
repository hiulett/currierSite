<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Gestión de Roles</h2>
            <p class="text-muted small mb-0">Define perfiles de acceso y asigna permisos específicos.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
            <button wire:click="createRole" class="btn btn-primary fw-black shadow-lg">
                <i class="align-middle me-1" data-feather="plus-circle"></i> CREAR NUEVO ROL
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 cursor-pointer" wire:click="sortBy('name')">
                                    Nombre del Rol
                                    @if($sortField === 'name')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th>Descripción</th>
                                <th class="text-center">Permisos</th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-black text-dark">{{ $role->name }}</div>
                                        @if($role->is_system) <span class="badge bg-secondary xsmall uppercase font-black">Sistema</span> @endif
                                    </td>
                                    <td class="small text-muted">{{ $role->description ?? 'Sin descripción.' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-light text-primary font-bold">{{ $role->permissions->count() }} permisos</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            <button wire:click="editRole({{ $role->id }})" class="btn btn-sm btn-light border shadow-sm" title="Editar">
                                                <i class="align-middle text-dark" data-feather="edit-2"></i>
                                            </button>
                                            @if(!$role->is_system)
                                                <button wire:click="deleteRole({{ $role->id }})" onclick="confirm('¿Estás seguro de eliminar este rol?') || event.stopImmediatePropagation()" class="btn btn-sm btn-light border shadow-sm text-danger" title="Eliminar">
                                                    <i class="align-middle" data-feather="trash-2"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted italic">No hay roles configurados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white py-4">
                    <h5 class="modal-title fw-black text-uppercase small tracking-widest text-white">
                        {{ $is_editing ? 'Editar Rol' : 'Nuevo Rol' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4 p-md-5">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label font-black text-uppercase small text-muted">Nombre del Rol</label>
                                <input type="text" wire:model="name" class="form-control form-control-lg border-2 fw-bold" placeholder="Ej: Operador de Miami">
                                @error('name') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label font-black text-uppercase small text-muted">Descripción</label>
                                <textarea wire:model="description" class="form-control border-2" rows="2"></textarea>
                            </div>

                            <div class="col-12">
                                <h6 class="fw-black text-uppercase small text-primary mb-4 mt-2">Permisos del Sistema</h6>
                                <div class="row g-4">
                                    @foreach($all_permissions as $group => $perms)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border-0 bg-light p-3 h-100" style="border-radius: 1rem;">
                                                <h6 class="fw-black text-muted xsmall uppercase mb-3 border-bottom pb-2">{{ $group }}</h6>
                                                @foreach($perms as $permission)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox"
                                                               wire:model="selected_permissions"
                                                               value="{{ $permission->id }}"
                                                               id="perm_{{ $permission->id }}">
                                                        <label class="form-check-label small" for="perm_{{ $permission->id }}">
                                                            {{ $permission->label }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-4">
                        <button type="button" class="btn btn-light border fw-bold text-uppercase" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-black text-uppercase shadow">
                            {{ $is_editing ? 'Actualizar Rol' : 'Crear Rol' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-role-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('roleModal')).show();
        });
        window.addEventListener('close-role-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('roleModal')).hide();
        });
    </script>
</div>
