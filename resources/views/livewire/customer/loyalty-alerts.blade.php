<div>
@if(count($alerts) > 0)
    @foreach($alerts as $index => $alert)
        <div class="modal fade" id="loyaltyAlert-{{ $loop->index }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header bg-warning bg-opacity-10 border-0">
                        <h5 class="modal-title text-uppercase font-black small text-dark"><i data-feather="gift" style="width: 14px;"></i> ¡Felicidades!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-3"
                             style="width: 56px; height: 56px; background-color: {{ $alert->data['color'] ?? '#f59e0b' }};">
                            <i data-feather="{{ $alert->data['icon'] ?? 'award' }}" style="width: 26px; height: 26px;"></i>
                        </div>
                        @if($alert->type === \App\Notifications\LevelUpNotification::class)
                            <h5 class="fw-bold text-dark mb-1">¡Subiste a <span style="color: {{ $alert->data['color'] ?? '#f59e0b' }}">{{ $alert->data['level'] }}</span>!</h5>
                            <p class="text-muted xsmall mb-0">
                                Ahora disfrutas <strong>{{ number_format($alert->data['free_pounds']) }} lb gratis</strong> de envío.
                            </p>
                        @else
                            <h5 class="fw-bold text-dark mb-1">¡Desbloqueaste "<span class="text-success">{{ $alert->data['reward'] }}</span>"!</h5>
                            <p class="text-muted xsmall mb-0">Ya puedes canjear esta recompensa con tus LOGYPUNTOS.</p>
                        @endif
                    </div>
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal"
                                wire:click="markRead('{{ $alert->id }}')">Cerrar</button>
                        <a href="{{ route('customer.rewards') }}" class="btn btn-primary px-4 fw-bold text-uppercase"
                           wire:click="markRead('{{ $alert->id }}')">Ver / Canjear Recompensa</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($alerts as $index => $alert)
                setTimeout(function () {
                    var el = document.getElementById('loyaltyAlert-{{ $loop->index }}');
                    if (el && window.bootstrap) {
                        bootstrap.Modal.getOrCreateInstance(el).show();
                    }
                }, {{ 600 + $loop->index * 600 }});
            @endforeach
        });
    </script>
@endif
</div>
