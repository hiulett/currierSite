<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($all_reports as $slug => $name)
            <div class="flex items-center justify-between p-4 bg-slate-900 rounded-2xl border border-slate-700">
                <span class="text-sm font-bold text-slate-300">{{ $name }}</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="" class="sr-only peer"
                           wire:click="toggleReport('{{ $slug }}')"
                           {{ in_array($slug, $enabled_reports) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
        @endforeach
    </div>
</div>
