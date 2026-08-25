<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Gestión de Usuarios</h2>
            <p class="text-muted small mb-0">Administra el acceso de tus colaboradores al sistema.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
            <button wire:click="createUser" class="btn btn-primary fw-black shadow-lg">
                <i class="align-middle me-1" data-feather="user-plus"></i> NUEVO USUARIO
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message"><strong>¡Error!</strong> {{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-light border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 uppercase font-black small">Colaboradores Activos</h5>
                    <div class="input-group input-group-sm" style="max-width: 250px;">
                        <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar por nombre...">
                        <span class="input-group-text bg-white"><i data-feather="search" style="width: 14px;"></i></span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 cursor-pointer" wire:click="sortBy('name')">
                                    Usuario
                                    @if($sortField === 'name')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="cursor-pointer" wire:click="sortBy('role_id')">
                                    Rol Asignado
                                    @if($sortField === 'role_id')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="text-center cursor-pointer" wire:click="sortBy('is_active')">
                                    Estado / Contraseña
                                    @if($sortField === 'is_active')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="text-center cursor-pointer" wire:click="sortBy('updated_at')">
                                    Última Conexión
                                    @if($sortField === 'updated_at')
                                        <i class="align-middle ms-1" data-feather="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </th>
                                <th class="pe-4 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary-light text-primary rounded-circle d-flex align-items-center justify-content-center font-bold me-2" style="width: 35px; height: 35px;">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-black text-dark leading-tight">
                                                    {{ $user->name }}
                                                    @if(auth()->id() === $user->id)
                                                        <span class="badge bg-primary-light text-primary fw-black ms-1" style="font-size: 0.55rem;">TÚ</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted xsmall font-bold">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($user->user_role)
                                            <span class="badge bg-dark-light text-dark fw-black text-uppercase" style="font-size: 0.6rem;">
                                                {{ $user->user_role->name }}
                                            </span>
                                        @else
                                            <span class="text-muted xsmall italic">Sin rol asignado</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->is_active)
                                            <span class="badge bg-success-light text-success fw-black text-uppercase" style="font-size: 0.6rem;">Activo</span>
                                        @else
                                            <span class="badge bg-danger-light text-danger fw-black text-uppercase" style="font-size: 0.6rem;">Inactivo</span>
                                        @endif

                                        @if($user->must_change_password)
                                            <span class="badge bg-warning-light text-warning fw-black text-uppercase ms-1" style="font-size: 0.6rem;" title="Debe cambiar contraseña en el primer inicio">Reinicio</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-muted small">
                                        {{ $user->updated_at ? $user->updated_at->diffForHumans() : 'Nunca' }}
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            <!-- Toggle Active State -->
                                            <button wire:click="toggleActive({{ $user->id }})" 
                                                    class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} border shadow-sm" 
                                                    title="{{ $user->is_active ? 'Desactivar' : 'Activar' }}"
                                                    @if(auth()->id() === $user->id) disabled @endif>
                                                <i class="align-middle" data-feather="{{ $user->is_active ? 'user-minus' : 'user-check' }}"></i>
                                            </button>

                                            <!-- Reset Password & Email -->
                                            <button wire:click="resetAndSendPassword({{ $user->id }})" 
                                                    class="btn btn-sm btn-light border shadow-sm" 
                                                    title="Restablecer y enviar contraseña por correo"
                                                    wire:confirm="¿Estás seguro de que deseas restablecer la contraseña de este usuario y enviársela por correo?">
                                                <i class="align-middle text-warning" data-feather="key"></i>
                                            </button>

                                            <!-- Edit -->
                                            <button wire:click="editUser({{ $user->id }})" class="btn btn-sm btn-light border shadow-sm" title="Editar">
                                                <i class="align-middle text-dark" data-feather="edit-2"></i>
                                            </button>

                                            <!-- Delete -->
                                            <button wire:click="confirmDeleteUser({{ $user->id }})" 
                                                    class="btn btn-sm btn-light border shadow-sm text-danger" 
                                                    title="Eliminar"
                                                    @if(auth()->id() === $user->id) disabled @endif>
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted italic">No hay usuarios administrativos registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header bg-primary text-white py-4">
                    <h5 class="modal-title fw-black text-uppercase small tracking-widest text-white">
                        {{ $is_editing ? 'Editar Usuario' : 'Nuevo Usuario' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-4 p-md-5">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label font-black text-uppercase small text-muted">Nombre Completo</label>
                                <input type="text" wire:model="name" class="form-control form-control-lg border-2 fw-bold">
                                @error('name') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label font-black text-uppercase small text-muted">Correo Electrónico</label>
                                <input type="email" wire:model="email" class="form-control border-2 fw-bold">
                                @error('email') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label font-black text-uppercase small text-muted">Rol de Acceso</label>
                                <select wire:model="role_id" class="form-select border-2 fw-bold">
                                    <option value="">Selecciona un rol...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                            </div>
                            
                            <!-- Opción Generación de Contraseña -->
                            @if(!$is_editing)
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="autoGeneratePassword" wire:model.live="auto_generate_password">
                                        <label class="form-check-label fw-bold text-dark small" for="autoGeneratePassword">
                                            Generar contraseña automáticamente
                                        </label>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Input Contraseña (oculto si se auto-genera) -->
                            @if($is_editing || !$auto_generate_password)
                                <div class="col-12">
                                    <label class="form-label font-black text-uppercase small text-muted">
                                        {{ $is_editing ? 'Nueva Contraseña (Opcional)' : 'Contraseña' }}
                                    </label>
                                    <input type="password" wire:model="password" class="form-control border-2">
                                    @error('password') <div class="text-danger xsmall mt-1">{{ $message }}</div> @enderror
                                    @if($is_editing)
                                        <div class="text-muted xsmall mt-1">Deja este campo en blanco si no deseas cambiar la contraseña.</div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Enviar credenciales por correo -->
                            @if(!$is_editing || $password)
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="sendCredentialsEmail" wire:model="send_credentials_email">
                                        <label class="form-check-label fw-bold text-dark small" for="sendCredentialsEmail">
                                            Enviar credenciales por correo electrónico
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <!-- Forzar cambio de contraseña -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="mustChangePassword" wire:model="must_change_password">
                                    <label class="form-check-label fw-bold text-dark small" for="mustChangePassword">
                                        Forzar cambio de contraseña en el primer inicio
                                    </label>
                                </div>
                            </div>

                            <!-- Estado Activo/Inactivo -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="isActive" wire:model="is_active" @if($is_editing && auth()->id() === $selected_user_id) disabled @endif>
                                    <label class="form-check-label fw-bold text-dark small" for="isActive">
                                        Usuario Activo (Permite el inicio de sesión)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-4">
                        <button type="button" class="btn btn-light border fw-bold text-uppercase" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-black text-uppercase shadow">
                            {{ $is_editing ? 'Actualizar Usuario' : 'Crear Usuario' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="modal-header bg-danger text-white py-3">
                    <h5 class="modal-title fw-black text-uppercase small text-white">Eliminar Colaborador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i data-feather="alert-triangle" class="text-danger mb-3" style="width: 48px; height: 48px;"></i>
                    <p class="mb-0 fw-bold">¿Estás seguro de que deseas eliminar a este colaborador de manera permanente?</p>
                    <p class="text-muted small mt-2">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer bg-light p-3 justify-content-center">
                    <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click="deleteUser" class="btn btn-danger fw-black">ELIMINAR</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('open-user-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('userModal')).show();
        });
        window.addEventListener('close-user-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('userModal')).hide();
        });
        window.addEventListener('open-delete-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).show();
        });
        window.addEventListener('close-delete-modal', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).hide();
        });
    </script>
</div>
