<div class="container-fluid p-0">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">{{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="row mb-2 align-items-center">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h4 mb-0 text-uppercase font-black tracking-tight text-dark">
                <i data-feather="gift" style="width: 22px; height: 22px; margin-top: -4px;"></i>
                Recompensas LOGYPUNTOS
            </h1>
            <p class="text-muted xsmall mb-0">Acumula puntos por cada libra enviada y canjéalos por beneficios.</p>
        </div>
        <div class="col-auto ms-auto text-end">
            <div class="card d-inline-block border-0 shadow-sm rounded-4 bg-warning text-dark px-3 py-1 mb-0" style="border-radius: 0.75rem;">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <p class="xsmall font-black text-uppercase mb-0 text-muted" style="font-size: 0.55rem;">Tu Saldo</p>
                        <h5 class="mb-0 fw-bold text-dark">{{ number_format($progress['balance']) }} <span class="xsmall">PUNTOS</span></h5>
                    </div>
                    <i data-feather="star" style="width: 18px; height: 18px; opacity: 0.6;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress / Balance Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(90deg, #fffbeb 0%, #ffffff 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-auto text-center mb-3 mb-md-0">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm mb-2"
                                 style="width: 64px; height: 64px; background-color: {{ $progress['currentLevel']->color ?? '#6c757d' }}; color: white;">
                                <i data-feather="{{ $progress['currentLevel']->icon ?? 'star' }}" style="width: 32px; height: 32px;"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-uppercase text-xs tracking-wide text-dark">{{ $progress['currentLevel']->name ?? 'Miembro' }}</h5>
                            @if($progress['currentLevel'] && $progress['currentLevel']->free_pounds > 0)
                                <span class="badge bg-success mt-1">{{ $progress['currentLevel']->free_pounds }} lb gratis</span>
                            @endif
                        </div>
                        <div class="col-md flex-grow-1 px-md-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <div>
                                    <h4 class="mb-0 fw-bold text-dark">{{ number_format($progress['balance']) }} <span class="xsmall text-muted fw-bold">PUNTOS ACUMULADOS</span></h4>
                                    @if($progress['pointsToNext'] !== null)
                                        <p class="mb-0 xsmall text-muted font-bold text-uppercase">
                                            Te faltan <span class="text-primary">{{ number_format($progress['pointsToNext']) }}</span> puntos para desbloquear
                                            @if($progress['nextLevel'])
                                                <span style="color: {{ $progress['nextLevel']->color }}">{{ $progress['nextLevel']->name }}</span>
                                            @endif
                                            @if($progress['nextReward'] && $progress['nextLevel'])
                                                /
                                            @endif
                                            @if($progress['nextReward'])
                                                "{{ $progress['nextReward']->name }}"
                                            @endif
                                        </p>
                                    @else
                                        <p class="mb-0 xsmall text-success font-bold text-uppercase">¡Desbloqueaste todas las recompensas disponibles!</p>
                                    @endif
                                </div>
                            </div>
                            <div class="progress" style="height: 12px; border-radius: 6px; background-color: #e9ecef;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                     style="width: {{ $progress['percent'] }}%; background-color: {{ $progress['currentLevel']->color ?? '#3b7ddd' }};"
                                     aria-valuenow="{{ $progress['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiers Overview -->
    <div class="row mb-4">
        @forelse($levels as $level)
            <div class="col-12 col-sm-6 col-xl-4 d-flex mb-3 mb-xl-0">
                <div class="card flex-fill border-0 shadow-sm rounded-4 {{ $progress['currentLevel'] && $progress['currentLevel']->id === $level->id ? 'border-start border-warning border-4' : '' }}">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge" style="background-color: {{ $level->color }}; color: white;">{{ $level->name }}</span>
                            <span class="xsmall text-muted font-bold text-uppercase">{{ number_format($level->min_points) }} pts</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">{{ $level->free_pounds }} lb de envío gratis</h6>
                        @if($progress['currentLevel'] && $progress['currentLevel']->id === $level->id)
                            <span class="xsmall text-success fw-bold text-uppercase mt-2 d-block"><i data-feather="check" style="width: 12px;"></i> Nivel actual</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info xsmall">Aún no hay niveles configurados.</div>
            </div>
        @endforelse
    </div>

    <!-- Reward Catalog -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-uppercase font-black small text-dark mb-0">Catálogo de Recompensas</h5>
    </div>
    <div class="row">
        @forelse($rewards as $reward)
            <div class="col-12 col-sm-6 col-xl-4 d-flex mb-4">
                <div class="card flex-fill border-0 shadow-sm rounded-4 {{ $reward->is_claimable ? 'border-start border-success border-4' : 'opacity-75' }}">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="stat {{ $reward->is_claimable ? 'bg-success-light text-success' : 'bg-light text-muted' }}">
                                <i data-feather="{{ $reward->icon ?? 'gift' }}"></i>
                            </div>
                            <span class="badge {{ $reward->is_claimable ? 'bg-success' : 'bg-secondary' }} text-uppercase xsmall px-2 py-1">
                                {{ $reward->is_claimable ? 'Canjeable' : 'Bloqueado' }}
                            </span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">{{ $reward->name }}</h6>
                        <p class="xsmall text-muted mb-0">{{ $reward->description }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="fw-bold text-warning">{{ number_format($reward->points_cost) }} <span class="xsmall text-muted">pts</span></span>
                            @if($reward->is_claimable)
                                <button wire:click="confirmRedeem({{ $reward->id }})" class="btn btn-success btn-sm fw-bold text-uppercase px-3 rounded-pill">
                                    <i data-feather="gift" style="width: 12px;"></i> Canjear
                                </button>
                            @else
                                <span class="xsmall text-muted">Te faltan {{ number_format($reward->points_cost - $progress['balance']) }} pts</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-5 text-center">
                        <i data-feather="package" class="text-muted mb-3" style="width: 48px; height: 48px; opacity: 0.2;"></i>
                        <p class="text-muted small">No hay recompensas disponibles por el momento.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- History -->
    <div class="row mt-2">
        <div class="col-12 col-xl-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 text-uppercase font-black small">Historial de Puntos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="xsmall text-muted text-uppercase">Concepto</th>
                                    <th class="xsmall text-muted text-uppercase text-end">Puntos</th>
                                    <th class="xsmall text-muted text-uppercase text-end">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pointsHistory as $entry)
                                    <tr>
                                        <td class="xsmall">{{ $entry->description }}</td>
                                        <td class="xsmall text-end fw-bold {{ $entry->points >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $entry->points >= 0 ? '+' : '' }}{{ number_format($entry->points) }}
                                        </td>
                                        <td class="xsmall text-muted text-end">{{ $entry->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted xsmall py-4">Aún no tienes movimientos de puntos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 text-uppercase font-black small">Mis Canjes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="xsmall text-muted text-uppercase">Recompensa</th>
                                    <th class="xsmall text-muted text-uppercase text-end">Costo</th>
                                    <th class="xsmall text-muted text-uppercase text-end">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($redemptions as $redemption)
                                    <tr>
                                        <td class="xsmall">
                                            {{ $redemption->reward_name }}
                                            @if($redemption->free_pounds_granted > 0)
                                                <span class="badge bg-success ms-1">{{ number_format($redemption->free_pounds_granted, 0) }} lb gratis</span>
                                            @endif
                                        </td>
                                        <td class="xsmall text-end fw-bold text-danger">{{ number_format($redemption->points_spent) }}</td>
                                        <td class="xsmall text-muted text-end">{{ $redemption->redeemed_at?->format('d M Y H:i') ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted xsmall py-4">Todavía no has canjeado recompensas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legal Terms -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="text-uppercase font-black small text-dark mb-3"><i data-feather="shield" style="width: 14px;"></i> Términos y Condiciones del Programa</h6>
            <p class="xsmall text-muted mb-2">
                LOGYPUNTOS aplica únicamente a clientes con tarifa regular. No aplica a revendedores ni clientes con tarifas especiales.
            </p>
            <p class="xsmall text-muted mb-0">
                Los puntos se acumulan por cada libra facturada, no tienen fecha de vencimiento y no pueden transferirse ni convertirse en dinero. Las recompensas en libras gratis se aplican sobre envíos futuros y están sujetas a disponibilidad.
            </p>
        </div>
    </div>

    <!-- Redeem Confirmation Modal -->
    @if($confirming_reward)
        <div class="modal fade show d-block" id="redeemConfirmModal" tabindex="-1" aria-hidden="true" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-uppercase font-black small text-dark"><i data-feather="gift" style="width: 14px;"></i> Confirmar Canje</h5>
                        <button type="button" class="btn-close" wire:click="cancelRedeem"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="stat bg-success-light text-success d-inline-flex align-items-center justify-content-center rounded-circle mx-auto mb-3" style="width: 56px; height: 56px;">
                            <i data-feather="award" style="width: 26px; height: 26px;"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">{{ $confirming_reward->name }}</h5>
                        <p class="text-muted xsmall mb-3">{{ $confirming_reward->description }}</p>
                        <div class="d-flex justify-content-center gap-3">
                            <div class="text-center">
                                <div class="fw-bold text-dark h5 mb-0">{{ number_format($confirming_reward->points_cost) }}</div>
                                <div class="xsmall text-muted text-uppercase">Puntos</div>
                            </div>
                            @if($confirming_reward->value > 0)
                                <div class="text-center">
                                    <div class="fw-bold text-success h5 mb-0">{{ number_format($confirming_reward->value, 0) }} lb</div>
                                    <div class="xsmall text-muted text-uppercase">Gratis</div>
                                </div>
                            @endif
                        </div>
                        <p class="xsmall text-muted mt-3 mb-0">
                            Tu saldo quedará en <strong>{{ number_format($progress['balance'] - $confirming_reward->points_cost) }}</strong> puntos.
                        </p>
                    </div>
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-light border" wire:click="cancelRedeem">Cancelar</button>
                        <button type="button" class="btn btn-success px-4 fw-bold text-uppercase" wire:click="redeem">
                            <i data-feather="check" style="width: 14px;"></i> Confirmar Canje
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
