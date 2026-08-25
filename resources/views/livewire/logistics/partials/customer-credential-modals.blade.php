<div>
    <div class="modal fade" id="passwordResetModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title uppercase font-black xsmall text-white">Nueva Contraseña</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="resetPassword">
                    <div class="modal-body p-4 text-center">
                        <div class="input-group mb-2">
                            <input type="text" wire:model="new_password" class="form-control border-2 text-center fw-bold" placeholder="Contraseña...">
                            <button class="btn btn-outline-dark border-2" type="button" wire:click="generateRandomPassword" title="Generar Aleatoria">
                                <i data-feather="refresh-cw" style="width: 14px;"></i>
                            </button>
                        </div>
                        <p class="xsmall text-muted mb-0">Esta clave será visible para ti y se enviará al cliente.</p>
                        @error('new_password') <div class="text-danger xsmall mt-2">{{ $message }}</div> @enderror
                    </div>
                    <div class="modal-footer bg-light p-2">
                        <button type="submit" class="btn btn-danger w-100 fw-black">ACTUALIZAR Y ENVIAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmPasswordModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-warning">
                        <i data-feather="mail" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="fw-black uppercase small">¿Enviar credenciales?</h5>
                    <p class="text-muted xsmall">Se enviará un correo con la contraseña actual al cliente.</p>
                </div>
                <div class="modal-footer bg-light p-2 gap-2 border-0">
                    <button type="button" class="btn btn-light border fw-bold flex-grow-1" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="button" wire:click="sendPasswordEmail" wire:loading.attr="disabled" class="btn btn-warning fw-black flex-grow-1">
                        <span wire:loading.remove wire:target="sendPasswordEmail">ENVIAR</span>
                        <span wire:loading wire:target="sendPasswordEmail" class="spinner-border spinner-border-sm" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
