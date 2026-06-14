<x-admin-layout title="Nova Ocorrência" subtitle="Registro interno de incidente">
    <x-slot:actions>
        <a href="{{ route('admin.tickets.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-zinc-700 text-zinc-400 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-zinc-800 transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Cancelar
        </a>
    </x-slot:actions>

    <form method="POST" action="{{ route('admin.tickets.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Coluna Principal -->
            <div class="xl:col-span-2 space-y-5">

                <!-- Problema -->
                <x-admin.section-card title="Dados da Ocorrência">
                    <div class="p-5 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2 space-y-2">
                                <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Categoria *</label>
                                <select name="category_id" required
                                        class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('category_id') border-red-500 @enderror">
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Prioridade</label>
                                <select name="priority"
                                        class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all">
                                    <option value="">Herdar da categoria</option>
                                    @foreach(\App\Enums\Priority::cases() as $p)
                                        <option value="{{ $p->value }}" {{ old('priority') === $p->value ? 'selected' : '' }}>
                                            {{ $p->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Atribuir para</label>
                                <select name="user_id"
                                        class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all">
                                    <option value="">Sem atribuição</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('user_id') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->name }} @if($agent->badge_number)({{ $agent->badge_number }})@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2" x-data="{ count: {{ strlen(old('description', '')) }} }">
                            <div class="flex items-center justify-between">
                                <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Descrição *</label>
                                <span class="text-[10px] text-zinc-600" x-text="count + '/1000'"></span>
                            </div>
                            <textarea id="description" name="description" rows="5" required maxlength="1000"
                                      @input="count = $event.target.value.length"
                                      placeholder="Descreva detalhadamente o problema..."
                                      class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-3 text-sm placeholder:text-zinc-600 focus:border-primary-variant focus:ring-primary-variant resize-none transition-all @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-admin.section-card>

                <!-- Localização -->
                <x-admin.section-card title="Localização">
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Endereço *</label>
                            <input type="text" name="address" value="{{ old('address') }}" required maxlength="255"
                                   placeholder="Rua, Número, Bairro..."
                                   class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm placeholder:text-zinc-600 focus:border-primary-variant focus:ring-primary-variant transition-all @error('address') border-red-500 @enderror">
                            @error('address') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Ponto de Referência</label>
                            <input type="text" name="reference_point" value="{{ old('reference_point') }}" maxlength="255"
                                   placeholder="Próximo a..."
                                   class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm placeholder:text-zinc-600 focus:border-primary-variant focus:ring-primary-variant transition-all">
                        </div>
                    </div>
                </x-admin.section-card>

                <!-- Fotos -->
                <x-admin.section-card title="Fotos (opcional)">
                    <div class="p-5" x-data="{
                        photos: [],
                        addPhotos(e) {
                            const incoming = Array.from(e.target.files).filter(f => f.type.startsWith('image/'));
                            this.photos = [...this.photos, ...incoming].slice(0, 5);
                            this.syncInput();
                            e.target.value = '';
                        },
                        removePhoto(index) {
                            this.photos.splice(index, 1);
                            this.syncInput();
                        },
                        syncInput() {
                            const dt = new DataTransfer();
                            this.photos.forEach(f => dt.items.add(f));
                            this.$refs.fileInput.files = dt.files;
                        },
                        previewUrl(file) { return URL.createObjectURL(file); }
                    }">
                        <input type="file" name="photos[]" multiple accept="image/*"
                               x-ref="fileInput" class="hidden" @change="addPhotos($event)">

                        <!-- Previews -->
                        <div class="flex flex-wrap gap-3 mb-4" x-show="photos.length > 0" x-cloak>
                            <template x-for="(photo, index) in photos" :key="index">
                                <div class="relative w-24 h-24 rounded-xl overflow-hidden border border-zinc-700 group flex-shrink-0">
                                    <img :src="previewUrl(photo)" class="w-full h-full object-cover">
                                    <button type="button" @click="removePhoto(index)"
                                            class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <!-- Botão de seleção -->
                        <button type="button" @click="$refs.fileInput.click()" x-show="photos.length < 5"
                                class="flex items-center gap-2 px-4 py-2 border border-zinc-700 text-zinc-400 rounded-xl text-sm font-bold hover:bg-zinc-800 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Adicionar fotos
                        </button>

                        <p class="text-xs text-zinc-600 mt-3"
                           x-text="photos.length + '/5 imagens · máx. 5MB cada'"></p>
                        @error('photos') <p class="text-red-400 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        @error('photos.*') <p class="text-red-400 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </x-admin.section-card>

            </div>

            <!-- Coluna Cidadão -->
            <div class="space-y-5">
                <x-admin.section-card title="Dados do Solicitante">
                    <div class="p-5 space-y-4">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Nome *</label>
                            <input type="text" name="citizen_name" value="{{ old('citizen_name') }}" required maxlength="255"
                                   class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('citizen_name') border-red-500 @enderror">
                            @error('citizen_name') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">E-mail *</label>
                            <input type="email" name="citizen_email" value="{{ old('citizen_email') }}" required maxlength="255"
                                   class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('citizen_email') border-red-500 @enderror">
                            @error('citizen_email') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2" x-data="inputMasks()">
                            <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Telefone</label>
                            <input type="text" name="citizen_phone" value="{{ old('citizen_phone') }}" maxlength="15" inputmode="numeric"
                                   @input="maskPhone($event)"
                                   class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all">
                        </div>
                        <div class="space-y-2" x-data="inputMasks()">
                            <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">CPF</label>
                            <input type="text" name="citizen_cpf" value="{{ old('citizen_cpf') }}" maxlength="14" inputmode="numeric"
                                   @input="maskCpf($event)" @blur="validateCpf($event)"
                                   class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('citizen_cpf') border-red-500 @enderror">
                            @error('citizen_cpf') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                            <p x-show="cpfError" x-text="cpfError" x-cloak class="text-red-400 text-xs font-bold"></p>
                        </div>
                    </div>
                </x-admin.section-card>

                <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-primary to-primary-variant rounded-xl font-black text-sm text-white uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-primary/30">
                    Registrar Ocorrência
                </button>
            </div>
        </div>
    </form>

</x-admin-layout>
