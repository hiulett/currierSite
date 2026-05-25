<div>
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="h3 mb-3 uppercase font-black tracking-tight">Configuración del Sistema</h1>
            <p class="text-muted">Define los parámetros operativos globales de tu empresa.</p>
        </div>
        <div class="col-md-4 text-end">
            <button wire:click="saveGeneral" class="btn btn-primary shadow-sm fw-black">
                <i class="align-middle me-2" data-feather="save"></i> GUARDAR CAMBIOS GENERALES
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
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small">Parámetros Financieros</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small font-black text-uppercase text-muted tracking-widest">Moneda del Sistema</label>
                        <select wire:model="currency" class="form-select form-select-lg border-2 fw-bold">
                            <option value="USD">USD - Dólar Estadounidense</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="PAB">PAB - Balboa Panameño</option>
                            <option value="COP">COP - Peso Colombiano</option>
                            <option value="MXN">MXN - Peso Mexicano</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small font-black text-uppercase text-muted tracking-widest">Impuesto (%)</label>
                            <div class="input-group input-group-lg">
                                <input type="number" step="0.1" wire:model="default_tax" class="form-control border-2 fw-bold">
                                <span class="input-group-text bg-light border-2 border-start-0">%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small font-black text-uppercase text-muted tracking-widest">Tarifa por Libra</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-2 border-end-0">&dollar;</span>
                                <input type="number" step="0.01" wire:model="default_rate" class="form-control border-2 fw-bold">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 uppercase font-black small">Región y Horario</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label small font-black text-uppercase text-muted tracking-widest">Idioma del Sistema</label>
                        <select wire:model="locale" class="form-select border-2 fw-bold">
                            <option value="es">Español (Castellano)</option>
                            <option value="en">English (International)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small font-black text-uppercase text-muted tracking-widest">Zona Horaria</label>
                        <select wire:model="timezone" class="form-select border-2">
                            <option value="America/Panama">Panamá (GMT-5)</option>
                            <option value="America/New_York">New York (GMT-5)</option>
                            <option value="America/Bogota">Colombia (GMT-5)</option>
                            <option value="UTC">UTC (Universal)</option>
                        </select>
                    </div>

                    <div class="p-3 bg-light rounded-3 border-dashed border-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle p-2 me-3">
                                <i data-feather="info" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-black text-uppercase small">Nota de Seguridad</h6>
                                <p class="mb-0 small text-muted">Estos cambios afectan a toda la organización inmediatamente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Locker Generation Settings -->
        <div class="col-12 mt-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 uppercase font-black small text-white"><i class="align-middle me-2" data-feather="grid"></i> Configuración de Casilleros Automáticos</h5>
                    <div class="d-flex align-items-center gap-2">
                        <label class="small uppercase font-black mb-0 opacity-50">Siguiente ID:</label>
                        <input type="number" wire:model="box_number_counter" class="form-control form-control-sm bg-transparent border-white border-opacity-25 text-white fw-bold" style="width: 100px;">
                        <button wire:click="saveCounter" class="btn btn-sm btn-outline-light fw-black uppercase">Guardar ID</button>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-5">
                        <!-- Air Section -->
                        <div class="col-lg-6 border-end">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-black text-uppercase text-primary mb-0"><i class="align-middle me-2" data-feather="send"></i> SERVICIO AÉREO</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model.live="service_air_enabled" id="airToggle">
                                    <label class="form-check-label xsmall fw-bold text-uppercase" for="airToggle">Activar</label>
                                </div>
                            </div>
                            <div class="mb-3 {{ !$service_air_enabled ? 'opacity-50' : '' }}">
                                <label class="form-label small font-black text-uppercase text-muted">Prefijo Aéreo</label>
                                <input type="text" wire:model="box_number_prefix_air" class="form-control border-2 fw-bold" placeholder="Ej: AIR, LGX" {{ !$service_air_enabled ? 'disabled' : '' }}>
                            </div>
                            <div class="mb-4 {{ !$service_air_enabled ? 'opacity-50' : '' }}">
                                <label class="form-label small font-black text-uppercase text-muted">Plantilla Identificador Aéreo</label>
                                <input type="text" wire:model="box_number_template_air" class="form-control border-2 fw-bold" {{ !$service_air_enabled ? 'disabled' : '' }}>
                                <div class="form-text xsmall">Variables: <code>{PREFIX}</code>, <code>{ID}</code>, <code>{NAME}</code></div>
                            </div>
                            <div class="p-3 bg-light rounded-3 border {{ !$service_air_enabled ? 'opacity-50' : '' }} mb-4">
                                <span class="xsmall text-muted uppercase font-bold d-block mb-1">Vista Previa Aérea:</span>
                                @if($service_air_enabled)
                                    <span class="h5 fw-black text-primary mb-0">
                                        {{ str_replace(['{PREFIX}', '{ID}', '{NAME}'], [$box_number_prefix_air, $box_number_counter + 1, 'JUAN PEREZ'], $box_number_template_air) }}
                                    </span>
                                @else
                                    <span class="xsmall text-muted italic">Servicio desactivado</span>
                                @endif
                            </div>
                            <div class="text-end">
                                <button wire:click="saveAir" class="btn btn-primary fw-black uppercase shadow-sm" {{ !$service_air_enabled ? 'disabled' : '' }}>
                                    Guardar Configuración Aérea
                                </button>
                            </div>
                        </div>

                        <!-- Maritime Section -->
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-black text-uppercase text-info mb-0"><i class="align-middle me-2" data-feather="anchor"></i> SERVICIO MARÍTIMO</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model.live="service_maritime_enabled" id="maritimeToggle">
                                    <label class="form-check-label xsmall fw-bold text-uppercase" for="maritimeToggle">Activar</label>
                                </div>
                            </div>
                            <div class="mb-3 {{ !$service_maritime_enabled ? 'opacity-50' : '' }}">
                                <label class="form-label small font-black text-uppercase text-muted">Prefijo Marítimo</label>
                                <input type="text" wire:model="box_number_prefix_maritime" class="form-control border-2 fw-bold" placeholder="Ej: SEA, MAR" {{ !$service_maritime_enabled ? 'disabled' : '' }}>
                            </div>
                            <div class="mb-4 {{ !$service_maritime_enabled ? 'opacity-50' : '' }}">
                                <label class="form-label small font-black text-uppercase text-muted">Plantilla Identificador Marítimo</label>
                                <input type="text" wire:model="box_number_template_maritime" class="form-control border-2 fw-bold" {{ !$service_maritime_enabled ? 'disabled' : '' }}>
                                <div class="form-text xsmall">Variables: <code>{PREFIX}</code>, <code>{ID}</code>, <code>{NAME}</code></div>
                            </div>
                            <div class="p-3 bg-light rounded-3 border {{ !$service_maritime_enabled ? 'opacity-50' : '' }} mb-4">
                                <span class="xsmall text-muted uppercase font-bold d-block mb-1">Vista Previa Marítima:</span>
                                @if($service_maritime_enabled)
                                    <span class="h5 fw-black text-info mb-0">
                                        {{ str_replace(['{PREFIX}', '{ID}', '{NAME}'], [$box_number_prefix_maritime, $box_number_counter + 1, 'JUAN PEREZ'], $box_number_template_maritime) }}
                                    </span>
                                @else
                                    <span class="xsmall text-muted italic">Servicio desactivado</span>
                                @endif
                            </div>
                            <div class="text-end">
                                <button wire:click="saveMaritime" class="btn btn-info fw-black uppercase shadow-sm text-white" {{ !$service_maritime_enabled ? 'disabled' : '' }}>
                                    Guardar Configuración Marítima
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
