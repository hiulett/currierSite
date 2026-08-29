<div class="mx-auto max-w-7xl px-4 py-6">
    @if (session()->has('message'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('message') }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">
            <i data-feather="gift" class="me-1" style="width: 22px; height: 22px; margin-top: -4px;"></i>
            Catálogo de Recompensas
        </h1>
        <p class="mt-1 text-sm text-slate-500">Gestiona las recompensas que los clientes ven y pueden canjear en el portal LOGYPUNTOS.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Formulario -->
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="rounded-t-2xl border-b border-slate-200 bg-slate-900 px-5 py-3">
                    <h5 class="text-sm font-bold uppercase tracking-wide text-white">{{ $is_editing ? 'Editar Recompensa' : 'Nueva Recompensa' }}</h5>
                </div>
                <form wire:submit.prevent="saveReward" class="space-y-4 p-5">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Nombre</label>
                        <input type="text" wire:model="name" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" placeholder="Ej: 3 lb gratis, Gorra LOGY...">
                        @error('name') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo</label>
                            <select wire:model="type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <option value="free_pounds">Libras Gratis</option>
                                <option value="physical">Artículo Físico</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Costo (Puntos)</label>
                            <input type="number" wire:model="points_cost" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" min="1" placeholder="300">
                            @error('points_cost') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Valor {{ $type === 'free_pounds' ? '(Libras Gratis)' : '(Cantidad / Unidades)' }}
                        </label>
                        <input type="number" wire:model="value" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" step="0.01" min="0" placeholder="Ej: 3">
                        @error('value') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Descripción</label>
                        <textarea wire:model="description" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" placeholder="Qué obtiene el cliente..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Icono (Feather)</label>
                            <input type="text" wire:model="icon" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" placeholder="gift, award, cup...">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Orden</label>
                            <input type="number" wire:model="sort_order" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" min="0" placeholder="0">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">URL de Imagen (opcional)</label>
                        <input type="text" wire:model="image_url" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" placeholder="https://...">
                    </div>

                    <div class="grid grid-cols-2 items-end gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Stock</label>
                            <input type="number" wire:model="stock" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" min="0" placeholder="Vacío = ilimitado">
                            <p class="mt-1 text-xs text-slate-400">Dejar vacío para stock ilimitado (libras gratis).</p>
                        </div>
                        <div class="pb-2">
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <input type="checkbox" wire:model="is_active" class="h-4 w-4 rounded border-slate-300 accent-blue-600">
                                Activa
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-bold uppercase tracking-wide text-white hover:bg-slate-800">
                            {{ $is_editing ? 'Actualizar' : 'Crear Recompensa' }}
                        </button>
                        @if($is_editing)
                            <button type="button" wire:click="resetForm" class="rounded-lg border border-slate-300 px-3 text-slate-500 hover:bg-slate-50">
                                <i data-feather="x" style="width: 18px; height: 18px;"></i>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla -->
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-3">
                    <h5 class="text-sm font-bold uppercase tracking-wide text-slate-900">Recompensas Configuradas</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Recompensa</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Costo</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Valor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Stock</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rewards as $reward)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @if($reward->image_url)
                                                <img src="{{ $reward->image_url }}" alt="{{ $reward->name }}" class="h-8 w-8 rounded-lg object-cover">
                                            @else
                                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                                    <i data-feather="{{ $reward->icon ?: 'gift' }}" style="width: 16px; height: 16px;"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="font-bold text-slate-900">{{ $reward->name }}</span>
                                                @if($reward->description)
                                                    <div class="text-xs text-slate-400">{{ \Illuminate\Support\Str::limit($reward->description, 40) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $reward->type === 'free_pounds' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $reward->type === 'free_pounds' ? 'Libras Gratis' : 'Físico' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-amber-600">{{ number_format($reward->points_cost) }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ number_format($reward->value, $reward->value == intval($reward->value) ? 0 : 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ $reward->stock === null ? '∞' : $reward->stock }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold uppercase {{ $reward->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $reward->is_active ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button wire:click="toggleActive({{ $reward->id }})" title="{{ $reward->is_active ? 'Desactivar' : 'Activar' }}" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100">
                                                <i data-feather="{{ $reward->is_active ? 'eye-off' : 'eye' }}" style="width: 14px; height: 14px;"></i>
                                            </button>
                                            <button wire:click="editReward({{ $reward->id }})" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100">
                                                <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                            </button>
                                            <button wire:click="deleteReward({{ $reward->id }})" wire:confirm="¿Seguro que quieres eliminar esta recompensa?" class="rounded-lg p-2 text-red-500 hover:bg-red-50">
                                                <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8">
                                        <x-empty-state message="No hay recompensas configuradas. Crea la primera para que aparezca en el portal de tus clientes." icon="package" />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 flex items-start gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                <i data-feather="info" class="mt-0.5 shrink-0" style="width: 16px; height: 16px;"></i>
                <span>
                    <strong>Tip:</strong> Las recompensas se cargan dinámicamente desde la base de datos en la pantalla
                    <strong>Recompensas LOGYPUNTOS</strong> del portal del cliente. Los clientes solo pueden canjear las que tengan
                    los puntos suficientes y estén activas con stock disponible.
                </span>
            </div>
        </div>
    </div>
</div>
