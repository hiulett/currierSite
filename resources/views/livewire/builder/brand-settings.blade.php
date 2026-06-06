<div>
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="h3 mb-3">Identidad de Marca</h1>
            <p class="text-muted">Define los colores, tipografía y modo visual de tu plataforma.</p>
        </div>
        <div class="col-md-4 text-end">
            <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary shadow-sm ms-auto">
                <span wire:loading.remove wire:target="save">
                    <i class="align-middle me-1" data-feather="save"></i> Guardar Cambios
                </span>
                <span wire:loading wire:target="save">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Procesando...
                </span>
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
            <div class="alert-message">
                <strong>¡Éxito!</strong> {{ session('message') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-lg-6">
            <!-- Basic Info & Logo -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">Nombre de la Empresa</label>
                        <input type="text" wire:model="company_name" class="form-control form-control-lg">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">Logotipo de la Empresa</label>
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $isPreviewable = false;
                                try {
                                    if ($logo && method_exists($logo, 'temporaryUrl')) {
                                        $logo->temporaryUrl();
                                        $isPreviewable = true;
                                    }
                                } catch (\Exception $e) { $isPreviewable = false; }
                            @endphp

                            @if($logo && $isPreviewable)
                                <img src="{{ $logo->temporaryUrl() }}" class="rounded border" style="width: 80px; height: 80px; object-fit: contain;">
                            @elseif($current_logo_url)
                                <img src="{{ $current_logo_url }}" class="rounded border" style="width: 80px; height: 80px; object-fit: contain;">
                            @else
                                <div class="bg-light rounded border d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i data-feather="image" class="text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" wire:model="logo" class="form-control">
                                <div wire:loading wire:target="logo" class="text-primary small mt-1">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Subiendo...
                                </div>
                                <div class="form-text">Recomendado: PNG/SVG fondo transparente, max 2MB.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colors -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Paleta de Colores</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-bold small text-uppercase">Color Primario</label>
                            <div class="d-flex align-items-center">
                                <input type="color" wire:model.live="primary_color" class="form-control form-control-color me-2 border-0 p-0" style="width: 45px; height: 45px; border-radius: 8px;">
                                <input type="text" wire:model.live="primary_color" class="form-control" placeholder="#000000">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-bold small text-uppercase">Color Secundario</label>
                            <div class="d-flex align-items-center">
                                <input type="color" wire:model.live="secondary_color" class="form-control form-control-color me-2 border-0 p-0" style="width: 45px; height: 45px; border-radius: 8px;">
                                <input type="text" wire:model.live="secondary_color" class="form-control" placeholder="#000000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typography -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tipografía</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">Fuente Principal</label>
                        <select wire:model="font_family" class="form-select form-select-lg">
                            <option value="inter">Inter (Profesional)</option>
                            <option value="poppins">Poppins (Creativa)</option>
                            <option value="roboto">Roboto (Estándar)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Login & Registration Customization -->
            <div class="card shadow-sm mb-4 border-primary" style="border-left-width: 4px;">
                <div class="card-header">
                    <h5 class="card-title mb-0">Personalización de Pantallas de Acceso</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">Slug de URL personalizada</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light xsmall text-muted">/access/</span>
                            <input type="text" wire:model="login_url_slug" class="form-control" placeholder="nombre-agencia">
                        </div>
                        <div class="form-text xsmall">Define una URL única para tus clientes (ej: /access/tu-marca).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">Título de Bienvenida</label>
                        <input type="text" wire:model="login_welcome_title" class="form-control" placeholder="Bienvenido a...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">Subtítulo o Instrucción</label>
                        <input type="text" wire:model="login_welcome_subtitle" class="form-control" placeholder="Acceso al Portal">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-bold small text-uppercase">Color de Fondo (Pantalla)</label>
                            <input type="color" wire:model="login_bg_color" class="form-control form-control-color w-100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-bold small text-uppercase">Mostrar Enlace de Registro</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" wire:model="show_register_link">
                                <span class="form-check-label xsmall text-muted">Habilitar creación de cuentas</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">CSS Personalizado</label>
                        <textarea wire:model="custom_css" class="form-control font-monospace xsmall" rows="4" placeholder=".btn-primary { border-radius: 0; }"></textarea>
                        <div class="form-text xsmall">Añade estilos adicionales a tu portal.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <!-- Theme Mode -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Modo de Visualización</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <button wire:click="$set('theme_mode', 'light')" class="w-100 p-3 rounded border-2 transition text-center d-block {{ $theme_mode == 'light' ? 'border-primary bg-light shadow-sm' : 'border-light bg-white hover:border-muted' }}" style="border-style: solid;">
                                <div class="bg-white border border-light mx-auto mb-2" style="height: 30px; width: 50px; border-radius: 4px;"></div>
                                <span class="small font-black text-uppercase {{ $theme_mode == 'light' ? 'text-primary' : 'text-muted' }}">Claro</span>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button wire:click="$set('theme_mode', 'dark')" class="w-100 p-3 rounded border-2 transition text-center d-block {{ $theme_mode == 'dark' ? 'border-primary bg-dark shadow-sm' : 'border-light bg-white hover:border-muted' }}" style="border-style: solid;">
                                <div class="bg-dark mx-auto mb-2 shadow-sm" style="height: 30px; width: 50px; border-radius: 4px;"></div>
                                <span class="small font-black text-uppercase {{ $theme_mode == 'dark' ? 'text-primary' : 'text-muted' }}">Oscuro</span>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button wire:click="$set('theme_mode', 'slate')" class="w-100 p-3 rounded border-2 transition text-center d-block {{ $theme_mode == 'slate' ? 'border-primary bg-dark shadow-sm' : 'border-light bg-white hover:border-muted' }}" style="border-style: solid; background-color: #1e293b !important;">
                                <div class="bg-slate mx-auto mb-2" style="height: 30px; width: 50px; border-radius: 4px; background-color: #0f172a;"></div>
                                <span class="small font-black text-uppercase {{ $theme_mode == 'slate' ? 'text-primary' : 'text-muted' }}">Slate</span>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button wire:click="$set('theme_mode', 'oceanic')" class="w-100 p-3 rounded border-2 transition text-center d-block {{ $theme_mode == 'oceanic' ? 'border-primary bg-dark shadow-sm' : 'border-light bg-white hover:border-muted' }}" style="border-style: solid; background-color: #0c4a6e !important;">
                                <div class="bg-info mx-auto mb-2" style="height: 30px; width: 50px; border-radius: 4px; background-color: #082f49;"></div>
                                <span class="small font-black text-uppercase {{ $theme_mode == 'oceanic' ? 'text-primary' : 'text-muted' }}">Oceánico</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Real-time Preview -->
            <div class="card shadow-lg bg-dark text-white overflow-hidden" style="border: 0;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="card-title text-white opacity-50 small mb-0">VISTA PREVIA EN TIEMPO REAL</h5>
                </div>
                <div class="card-body p-4">
                    <div class="p-4 rounded-3 shadow-sm" style="background-color: {{ $theme_mode == 'light' ? '#f8f9fa' : ($theme_mode == 'dark' ? '#11172b' : ($theme_mode == 'slate' ? '#0f172a' : '#0c4a6e')) }}; border: 1px solid rgba(255,255,255,0.05);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-2 rounded-2 me-2" style="background-color: {{ $primary_color }}; color: white;">
                                <i data-feather="box" style="width: 16px; height: 16px;"></i>
                            </div>
                            <span class="fw-bold h5 mb-0" style="color: {{ $theme_mode == 'light' ? '#212529' : 'white' }};">{{ $company_name }}</span>
                        </div>

                        <div class="mb-4">
                            <div class="bg-muted opacity-25 rounded mb-2" style="height: 8px; width: 80%; background-color: gray;"></div>
                            <div class="bg-muted opacity-25 rounded mb-2" style="height: 8px; width: 60%; background-color: gray;"></div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <button class="btn w-100 shadow-sm" style="background-color: {{ $primary_color }}; color: white; border: 0; font-size: 11px; font-weight: 800;">BOTÓN PRIMARIO</button>
                            </div>
                            <div class="col-6">
                                <div class="badge w-100 py-2" style="background-color: {{ $primary_color }}15; color: {{ $primary_color }}; font-size: 10px;">BADGE LIGHT</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4 text-center">
                    <p class="text-white-50 small mb-0">Los cambios se aplican globalmente al guardar.</p>
                </div>
            </div>

            <!-- Login & Registration Customization -->
            <div class="card shadow-sm mb-4 border-primary" style="border-left-width: 4px;">
                <div class="card-header">
                    <h5 class="card-title mb-0">Personalización de Pantallas de Acceso</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">Slug de URL personalizada</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light xsmall text-muted">/access/</span>
                            <input type="text" wire:model="login_url_slug" class="form-control" placeholder="nombre-agencia">
                        </div>
                        <div class="form-text xsmall">Define una URL única para tus clientes (ej: /access/tu-marca).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">Título de Bienvenida</label>
                        <input type="text" wire:model="login_welcome_title" class="form-control" placeholder="Bienvenido a...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">Subtítulo o Instrucción</label>
                        <input type="text" wire:model="login_welcome_subtitle" class="form-control" placeholder="Acceso al Portal">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-bold small text-uppercase">Color de Fondo (Pantalla)</label>
                            <input type="color" wire:model="login_bg_color" class="form-control form-control-color w-100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-bold small text-uppercase">Mostrar Enlace de Registro</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" wire:model="show_register_link">
                                <span class="form-check-label xsmall text-muted">Habilitar creación de cuentas</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-bold small text-uppercase">CSS Personalizado</label>
                        <textarea wire:model="custom_css" class="form-control font-monospace xsmall" rows="4" placeholder=".btn-primary { border-radius: 0; }"></textarea>
                        <div class="form-text xsmall">Añade estilos adicionales a tu portal.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
