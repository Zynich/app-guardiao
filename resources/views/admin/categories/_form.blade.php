@php $category = $category ?? null; @endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2 space-y-2">
        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Nome *</label>
        <input type="text" name="name" value="{{ old('name', $category?->name) }}" required
               class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('name') border-red-500 @enderror">
        @error('name') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2 space-y-2">
        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Descrição</label>
        <textarea name="description" rows="3"
                  placeholder="Descreva brevemente quais problemas se enquadram nesta categoria..."
                  class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-3 text-sm placeholder:text-zinc-600 focus:border-primary-variant focus:ring-primary-variant resize-none transition-all">{{ old('description', $category?->description) }}</textarea>
    </div>

    <div class="space-y-2">
        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Prioridade Padrão *</label>
        <select name="priority" required
                class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('priority') border-red-500 @enderror">
            @foreach(\App\Enums\Priority::cases() as $p)
                <option value="{{ $p->value }}" {{ old('priority', $category?->priority?->value) === $p->value ? 'selected' : '' }}>
                    {{ $p->label() }}
                </option>
            @endforeach
        </select>
        @error('priority') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">SLA (horas) *</label>
        <input type="number" name="sla_hours" value="{{ old('sla_hours', $category?->sla_hours ?? 72) }}"
               min="1" max="8760" required
               class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('sla_hours') border-red-500 @enderror">
        <p class="text-[10px] text-zinc-600">Prazo máximo para resolução. Ex: 24h, 72h, 168h (1 semana).</p>
        @error('sla_hours') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Categoria Pai (opcional)</label>
        <select name="parent_id"
                class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all">
            <option value="">Categoria raiz</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id', $category?->parent_id) == $parent->id ? 'selected' : '' }}>
                    {{ $parent->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex items-center">
        <label class="flex items-center gap-3 cursor-pointer group">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-zinc-600 bg-surface-darker text-primary-variant focus:ring-primary-variant">
            <span class="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors">
                Categoria ativa (visível no portal)
            </span>
        </label>
    </div>
</div>
