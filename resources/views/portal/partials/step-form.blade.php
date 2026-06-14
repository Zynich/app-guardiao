@props(['categories'])

<div x-show="step > 0 && step < 4" x-cloak
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 transform translate-y-8"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     class="w-full max-w-4xl">

    <x-ui.card class="p-8 rounded-3xl w-full border border-zinc-800/50 backdrop-blur-xl bg-surface/40 shadow-2xl">

        <!-- Header do Form -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-zinc-800/50 pb-8">
            <div>
                <h3 class="text-2xl font-black text-white flex items-center gap-3">
                    <span class="bg-primary-variant/10 text-primary-variant p-2 rounded-lg">
                        <svg x-show="step === 1" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <svg x-show="step === 2" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="step === 3" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <span x-text="step === 1 ? 'O Problema' : (step === 2 ? 'Localização' : 'Contato & Mídia')"></span>
                </h3>
            </div>

            <!-- Steps Indicator -->
            <div class="flex items-center gap-4">
                <template x-for="i in 3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full transition-all duration-500"
                            :class="step >= i ? 'bg-primary-variant shadow-[0_0_10px_rgba(23,162,184,0.5)]' : 'bg-zinc-800'">
                        </div>
                        <div x-show="i < 3" class="w-8 h-[2px] mx-1 transition-colors duration-500"
                            :class="step > i ? 'bg-primary-variant' : 'bg-zinc-800'"></div>
                    </div>
                </template>
                <span class="ml-4 text-xs font-black text-zinc-500 uppercase tracking-widest bg-zinc-800/50 px-3 py-1.5 rounded-full border border-zinc-700/50">
                    Etapa <span x-text="step" class="text-white"></span> de 3
                </span>
            </div>
        </div>

        <!-- Form Steps Content -->
        <div class="min-h-[300px]">

            <!-- ── Step 1: O Problema ─────────────────────────────── -->
            <div x-show="step === 1"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="space-y-8">

                <p class="text-zinc-400 font-medium leading-relaxed">
                    Selecione a categoria que melhor descreve o incidente e nos dê detalhes do que está acontecendo.
                </p>

                <div class="space-y-6">
                    <!-- Seleção de Categoria -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-1">
                            <x-ui.label value="Qual o problema?" class="text-white font-black" />
                            <span class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest"
                                  x-text="formData.category_id ? 'Toque para mudar' : 'Toque para selecionar'"></span>
                        </div>

                        <!-- Categoria selecionada -->
                        <div x-show="formData.category_id && !showCategories"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="flex justify-center py-2">
                            <button type="button" @click="showCategories = true"
                                    class="group p-6 rounded-2xl border bg-primary-variant/10 border-primary-variant/50 shadow-[0_0_20px_rgba(23,162,184,0.15)] flex flex-col items-center justify-center gap-3 transition-all hover:bg-primary-variant/20 hover:border-primary-variant min-w-[200px]">
                                <div class="bg-primary-variant text-white p-2 rounded-lg shadow-lg">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-black text-white uppercase tracking-tighter" x-text="formData.category_name"></span>
                                <span class="text-[10px] text-primary-variant font-black uppercase tracking-[0.2em] opacity-80 underline underline-offset-4">Toque para trocar</span>
                            </button>
                        </div>

                        <!-- Busca + grid de categorias -->
                        <div x-show="!formData.category_id || showCategories"
                             class="space-y-4"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             @click.away="if(formData.category_id) showCategories = false">

                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-zinc-500 group-focus-within:text-primary-variant transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input x-model="search" @focus="showCategories = true" type="text"
                                       placeholder="Resuma o problema em uma palavra..."
                                       class="w-full bg-surface-darker/60 border border-zinc-800 text-white rounded-2xl pl-12 pr-4 py-3 text-sm focus:border-primary-variant/50 focus:ring-1 focus:ring-primary-variant/50 transition-all placeholder:text-zinc-600 font-bold shadow-inner">
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-[250px] overflow-y-auto pr-2 p-1">
                                @foreach ($categories as $category)
                                    <button type="button"
                                            x-show="'{{ strtolower($category->name) }}'.includes(search.toLowerCase())"
                                            @click="formData.category_id = '{{ $category->id }}'; formData.category_name = '{{ $category->name }}'; showCategories = false; search = ''"
                                            class="p-4 rounded-xl border transition-all duration-300 flex flex-col items-center justify-center text-center gap-2 group/card bg-surface-darker/40 border-zinc-800/50 hover:border-primary-variant/40 hover:bg-surface-darker shadow-sm active:scale-95">
                                        <span class="text-[11px] font-black uppercase tracking-tight text-zinc-400 group-hover/card:text-zinc-100">{{ $category->name }}</span>
                                    </button>
                                @endforeach
                                @if($categories->isEmpty())
                                    @foreach(['Buraco na Via', 'Iluminação', 'Vazamento Água', 'Lixo Acumulado', 'Poda Árvore', 'Obras Paradas'] as $index => $mock)
                                        <div class="p-4 rounded-xl border bg-surface-darker/40 border-zinc-800/50 flex items-center justify-center text-center opacity-40">
                                            <span class="text-[11px] font-black uppercase tracking-tight text-zinc-500">{{ $mock }}</span>
                                        </div>
                                    @endforeach
                                    <div class="col-span-full text-center text-xs text-yellow-500/70 font-bold py-2">
                                        Nenhuma categoria cadastrada. Execute o seeder de categorias.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Erro categoria -->
                        <p x-show="hasError('category_id')" x-text="getError('category_id')"
                           class="text-red-400 text-xs font-bold mt-1 px-1"></p>
                    </div>

                    <!-- Descrição -->
                    <div class="space-y-3 pt-4 border-t border-zinc-800/30">
                        <div class="flex items-center justify-between px-1">
                            <x-ui.label value="Descrição Detalhada" class="text-white font-black" />
                            <span class="text-[10px] font-bold tracking-widest uppercase"
                                  :class="formData.description.length > charLimit ? 'text-red-500' : 'text-zinc-500'">
                                <span x-text="formData.description.length"></span>/<span x-text="charLimit"></span>
                            </span>
                        </div>
                        <x-ui.textarea x-model="formData.description" maxlength="1000"
                            placeholder="Descreva aqui o problema de forma clara..." />
                        <p x-show="hasError('description')" x-text="getError('description')"
                           class="text-red-400 text-xs font-bold mt-1 px-1"></p>
                    </div>
                </div>
            </div>

            <!-- ── Step 2: Localização ─────────────────────────────── -->
            <div x-show="step === 2"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="space-y-8">

                <p class="text-zinc-400 font-medium leading-relaxed">
                    Onde exatamente isso está acontecendo? O endereço e um ponto de referência ajudam muito nossos agentes.
                </p>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <x-ui.label value="Endereço aproximado" class="text-zinc-500 ml-1" />
                        <x-ui.input x-model="formData.address" placeholder="Rua, Número, Bairro..." class="w-full">
                            <x-slot:icon>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </x-slot:icon>
                        </x-ui.input>
                        <p x-show="formData.latitude && !hasError('address')"
                           class="text-zinc-500 text-xs font-medium mt-1 px-1">
                            Endereço preenchido pelo mapa — adicione o número se necessário.
                        </p>
                        <p x-show="hasError('address')" x-text="getError('address')"
                           class="text-red-400 text-xs font-bold mt-1 px-1"></p>
                    </div>

                    <div class="space-y-3">
                        <x-ui.label value="Ponto de Referência" class="text-zinc-500 ml-1" />
                        <x-ui.input x-model="formData.reference_point" placeholder="Próximo a..." class="w-full">
                            <x-slot:icon>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </x-slot:icon>
                        </x-ui.input>
                    </div>
                </div>

                <!-- Mapa Leaflet -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <x-ui.label value="Marque no Mapa (opcional)" class="text-zinc-500" />
                        <span x-show="formData.latitude" class="text-[10px] text-green-400 font-black uppercase tracking-widest">
                            Localização marcada
                        </span>
                    </div>
                    <div class="rounded-2xl overflow-hidden border border-zinc-800 relative" style="height: 260px;">
                        <div id="leaflet-map" class="w-full h-full z-0"></div>
                        <!-- Overlay inicial antes de clicar -->
                        <div x-show="!formData.latitude"
                             class="absolute inset-0 bg-zinc-900/60 backdrop-blur-sm flex flex-col items-center justify-center gap-3 pointer-events-none z-10">
                            <svg class="w-8 h-8 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <span class="text-sm font-bold text-zinc-400">Toque no mapa para marcar ou use sua localização</span>
                        </div>
                    </div>
                    <button type="button" @click="useMyLocation()"
                            class="w-full flex items-center justify-center gap-2 py-3 rounded-xl border border-zinc-700 text-zinc-400 text-sm font-bold uppercase tracking-widest hover:border-primary-variant hover:text-primary-variant transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Usar Minha Localização
                    </button>
                </div>
            </div>

            <!-- ── Step 3: Contato & Mídia ─────────────────────────── -->
            <div x-show="step === 3"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="space-y-8">

                <p class="text-zinc-400 font-medium leading-relaxed">
                    Quase lá! Precisamos apenas dos seus contatos para enviar atualizações do protocolo.
                </p>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <x-ui.label value="Nome Completo" class="text-zinc-500 ml-1" />
                            <x-ui.input x-model="formData.name" placeholder="Seu nome" class="w-full">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </x-slot:icon>
                            </x-ui.input>
                            <p x-show="hasError('citizen_name')" x-text="getError('citizen_name')"
                               class="text-red-400 text-xs font-bold mt-1 px-1"></p>
                        </div>
                        <div class="space-y-3">
                            <x-ui.label value="CPF (opcional)" class="text-zinc-500 ml-1" />
                            <x-ui.input x-model="formData.cpf" placeholder="000.000.000-00" class="w-full">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.333 0 4 1 4 3" />
                                    </svg>
                                </x-slot:icon>
                            </x-ui.input>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <x-ui.label value="E-mail" class="text-zinc-500 ml-1" />
                            <x-ui.input x-model="formData.email" type="email" placeholder="seu@email.com" class="w-full">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </x-slot:icon>
                            </x-ui.input>
                            <p x-show="hasError('citizen_email')" x-text="getError('citizen_email')"
                               class="text-red-400 text-xs font-bold mt-1 px-1"></p>
                        </div>
                        <div class="space-y-3">
                            <x-ui.label value="Telefone (opcional)" class="text-zinc-500 ml-1" />
                            <x-ui.input x-model="formData.phone" placeholder="(00) 00000-0000" class="w-full">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </x-slot:icon>
                            </x-ui.input>
                        </div>
                    </div>
                </div>

                <!-- Upload de Fotos -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <x-ui.label value="Fotos do Problema (opcional)" class="text-zinc-500" />
                        <span class="text-[10px] font-bold text-zinc-600 uppercase tracking-widest">
                            <span x-text="formData.photos.length"></span>/5
                        </span>
                    </div>

                    <!-- Input de arquivo oculto -->
                    <input type="file" id="photos-input" multiple accept="image/*"
                           @change="handleFileSelect($event)" class="hidden">

                    <!-- Dropzone -->
                    <div @dragover.prevent="dragOver = true"
                         @dragleave.prevent="dragOver = false"
                         @drop="handleDrop($event)"
                         @click="document.getElementById('photos-input').click()"
                         x-show="formData.photos.length < 5"
                         :class="dragOver ? 'border-primary-variant bg-primary-variant/5' : 'border-zinc-700 hover:border-primary-variant/50 hover:bg-zinc-800/50'"
                         class="bg-zinc-900/50 border border-dashed rounded-2xl h-32 flex flex-col items-center justify-center text-zinc-500 gap-3 cursor-pointer transition-all duration-300">
                        <div :class="dragOver ? 'bg-primary-variant text-white' : 'bg-zinc-800'"
                             class="p-3 rounded-full transition-all shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="flex flex-col items-center pointer-events-none">
                            <span class="text-sm font-black text-zinc-100">Anexar Fotos do Problema</span>
                            <span class="text-[10px] uppercase tracking-wider opacity-60">Arraste aqui ou clique para selecionar</span>
                        </div>
                    </div>

                    <!-- Pré-visualização das fotos -->
                    <div x-show="formData.photos.length > 0" class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        <template x-for="(photo, index) in formData.photos" :key="index">
                            <div class="relative group aspect-square rounded-xl overflow-hidden border border-zinc-700">
                                <img :src="photoPreviewUrl(photo)" class="w-full h-full object-cover">
                                <button type="button" @click.stop="removePhoto(index)"
                                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div x-show="photoFeedback > 0"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-end="opacity-0"
                         class="flex items-center gap-2 text-green-400 text-xs font-bold px-1 mt-1">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="photoFeedback === 1 ? '1 foto adicionada!' : photoFeedback + ' fotos adicionadas!'"></span>
                    </div>

                    <p x-show="hasError('photos')" x-text="getError('photos')"
                       class="text-red-400 text-xs font-bold mt-1 px-1"></p>
                </div>
            </div>

            <div x-show="hasError('recaptcha_token')"
                 class="mt-4 p-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm text-center">
                Verificação de segurança falhou. Clique em <strong>Finalizar Relato</strong> novamente para tentar.
            </div>

        </div><!-- /steps content -->

        <!-- Footer de Navegação -->
        <div class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-zinc-800/50 pt-8">

            <button type="button" @click="step > 1 ? step-- : step = 0"
                    class="w-full sm:w-auto px-10 py-3.5 rounded-xl border border-zinc-800 text-zinc-400 font-bold hover:bg-zinc-800 hover:text-white transition-all active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar
            </button>

            <!-- Avançar (steps 1 e 2) -->
            <x-ui.button x-show="step < 3" type="button" @click="step++" class="w-full sm:w-auto px-12 py-3.5">
                <span class="flex items-center gap-2">
                    Avançar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </x-ui.button>

            <!-- Finalizar (step 3) -->
            <x-ui.button x-show="step === 3" type="button" @click="submitTicket()" x-bind:disabled="loading || photoFeedback > 0"
                         class="w-full sm:w-auto px-12 py-3.5">
                <span x-show="!loading" class="flex items-center gap-2">
                    <template x-if="photoFeedback > 0">
                        <span>Aguarde...</span>
                    </template>
                    <template x-if="photoFeedback === 0">
                        <span class="flex items-center gap-2">
                            Finalizar Relato
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </template>
                </span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <x-ui.spinner class="w-4 h-4 text-white" />
                    Enviando...
                </span>
            </x-ui.button>

        </div>
    </x-ui.card>
</div>
