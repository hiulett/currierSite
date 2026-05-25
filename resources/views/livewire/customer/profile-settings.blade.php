<div class="container-fluid p-0">
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            #map { cursor: crosshair; }
        </style>
    @endpush

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h3 mb-0 uppercase font-black tracking-tight text-dark">Mi Perfil</h2>
            <p class="text-muted small">Gestiona tu información personal, datos de contacto y seguridad.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Datos Personales</h5>
                </div>
                <div class="card-body">
                    @if (session()->has('profile_message'))
                        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
                            <div class="alert-message"><strong>¡Éxito!</strong> {{ session('profile_message') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="updateProfile">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="inputName">Nombre Completo</label>
                                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="inputName" placeholder="Nombre completo">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="inputEmail">Correo Electrónico</label>
                                <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail" placeholder="Correo electrónico">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="inputPhone">Teléfono de Contacto</label>
                                <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" id="inputPhone" placeholder="Ej: +507 6666-6666">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="inputID">Número de Identificación (Cédula/RUC)</label>
                                <input type="text" wire:model="identification_number" class="form-control @error('identification_number') is-invalid @enderror" id="inputID" placeholder="Número de identificación">
                                @error('identification_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="address_field">Dirección de Entrega</label>
                            <textarea wire:model="address" id="address_field" rows="2" class="form-control @error('address') is-invalid @enderror" placeholder="Indica dónde deseas recibir tus paquetes localmente..."></textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Map Section -->
                        <div class="mb-3">
                            <label class="form-label">Selecciona tu ubicación en el mapa</label>
                            <div wire:ignore id="map" class="rounded border" style="height: 300px; width: 100%;"></div>
                            <input type="hidden" wire:model="latitude" id="lat">
                            <input type="hidden" wire:model="longitude" id="lng">
                            <p class="text-muted small mt-2"><i data-feather="info" class="me-1" style="width: 12px;"></i> Haz clic en el mapa para marcar tu ubicación exacta.</p>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                    </form>
                </div>
            </div>

            <!-- Security / Password -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Seguridad</h5>
                </div>
                <div class="card-body">
                    @if (session()->has('password_message'))
                        <div class="alert alert-success alert-dismissible shadow-sm mb-4" role="alert">
                            <div class="alert-message"><strong>¡Listo!</strong> {{ session('password_message') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="updatePassword">
                        <div class="mb-3">
                            <label class="form-label" for="inputPasswordCurrent">Contraseña Actual</label>
                            <input type="password" wire:model="current_password" class="form-control @error('current_password') is-invalid @enderror" id="inputPasswordCurrent">
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="inputPasswordNew">Nueva Contraseña</label>
                                <input type="password" wire:model="new_password" class="form-control @error('new_password') is-invalid @enderror" id="inputPasswordNew">
                                @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="inputPasswordNew2">Confirmar Nueva Contraseña</label>
                                <input type="password" wire:model="new_password_confirmation" class="form-control" id="inputPasswordNew2">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                    </form>
                </div>
            </div>

            <div class="text-center pb-5">
                <p class="text-muted small uppercase font-bold tracking-widest">¿Deseas cerrar tu cuenta definitivamente? <a href="#" class="text-danger">Contacta a soporte</a></p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('livewire:navigated', function() {
                initMap();
            });

            document.addEventListener('DOMContentLoaded', function() {
                initMap();
            });

            function initMap() {
                const mapEl = document.getElementById('map');
                if (!mapEl || mapEl._leaflet_id) return;

                let lat = @js($latitude) || 8.9833; // Default to Panama City if empty
                let lng = @js($longitude) || -79.5167;
                let zoom = @js($latitude) ? 16 : 12;

                const map = L.map('map').setView([lat, lng], zoom);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                let marker = null;
                if (@js($latitude)) {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                map.on('click', function(e) {
                    const { lat, lng } = e.latlng;

                    if (marker) {
                        marker.setLatLng(e.latlng);
                    } else {
                        marker = L.marker(e.latlng).addTo(map);
                    }

                    @this.set('latitude', lat);
                    @this.set('longitude', lng);

                    // Optional: Reverse Geocoding to get address name
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.display_name) {
                                @this.set('address', data.display_name);
                            }
                        })
                        .catch(err => console.warn('Geocoding error:', err));
                });
            }
        </script>
    @endpush
</div>
