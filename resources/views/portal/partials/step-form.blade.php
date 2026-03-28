@props(['categories'])

<div x-show="step > 0" x-cloak x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 transform translate-y-8"
    x-transition:enter-end="opacity-100 transform translate-y-0" class="w-full max-w-4xl">
    <x-ui.card class="p-8 rounded-3xl w-full border border-zinc-800/50 backdrop-blur-xl bg-surface/40 shadow-2xl">
        <!-- Header do Form -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-zinc-800/50 pb-8">
            <div>
                <h3 class="text-2xl font-black text-white flex items-center gap-3">
                    <span class="bg-primary-variant/10 text-primary-variant p-2 rounded-lg">
                        <svg x-show="step === 1" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <svg x-show="step === 2" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="step === 3" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
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
                <span
                    class="ml-4 text-xs font-black text-zinc-500 uppercase tracking-widest bg-zinc-800/50 px-3 py-1.5 rounded-full border border-zinc-700/50">
                    Etapa <span x-text="step" class="text-white"></span> de 3
                </span>
            </div>
        </div>

        <!-- Form Steps Content -->
        <div class="min-h-[300px]">
            <!-- Step 1: O Problema -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0"
                class="space-y-8">
                <p class="text-zinc-400 font-medium leading-relaxed">
                    Selecione a categoria que melhor descreve o incidente e nos dê detalhes do que está acontecendo.
                </p>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <x-ui.label value="Categoria do Problema" class="text-zinc-500 ml-1" />
                        <x-ui.select name="category_id" placeholder="Selecione o tipo de incidente"
                            @change="formData.category_id = $event.detail">
                            @foreach ($categories as $category)
                                <button type="button" @click="select('{{ $category->id }}', '{{ $category->name }}')"
                                    class="w-full text-left px-4 py-3 text-sm text-zinc-300 hover:bg-primary-variant/10 hover:text-primary-variant rounded-lg transition-all duration-200 flex items-center justify-between group/opt">
                                    <span>{{ $category->name }}</span>
                                    <svg x-show="selected == '{{ $category->id }}'" class="w-4 h-4 text-primary-variant"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endforeach
                        </x-ui.select>
                    </div>

                    <div class="space-y-3">
                        <x-ui.label value="Descrição dos Detalhes" class="text-zinc-500 ml-1" />
                        <x-ui.textarea x-model="formData.description"
                            placeholder="Descreva aqui o problema de forma clara..." />
                    </div>
                </div>
            </div>

            <!-- Step 2: Localização -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0"
                class="space-y-8">
                <p class="text-zinc-400 font-medium leading-relaxed">
                    Onde exatamente isso está acontecendo? O endereço e um ponto de referência ajudam muito nossos
                    agentes.
                </p>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <x-ui.label value="Endereço aproximado" class="text-zinc-500 ml-1" />
                        <x-ui.input x-model="formData.address" placeholder="Rua, Número, Bairro..." class="w-full">
                            <x-slot:icon>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </x-slot:icon>
                        </x-ui.input>
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

                <!-- Map Placeholder -->
                <div
                    class="bg-zinc-900/50 border border-dashed border-zinc-700 rounded-2xl h-48 flex flex-col items-center justify-center text-zinc-500 gap-4 overflow-hidden relative group">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    <svg class="w-12 h-12 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <span class="text-sm font-bold uppercase tracking-tighter">O Mapa será injetado aqui</span>
                    <x-ui.button class="z-10 text-xs py-2 px-6 bg-zinc-800 border border-zinc-700 hover:bg-zinc-700">
                        <span class="flex items-center gap-2">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Usar Minha Localização
                        </span>
                    </x-ui.button>
                </div>
            </div>

            <!-- Step 3: Contatos & Mídia -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0"
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
                        </div>
                        <div class="space-y-3">
                            <x-ui.label value="CPF" class="text-zinc-500 ml-1" />
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
                            <x-ui.input x-model="formData.email" type="email" placeholder="seu@email.com"
                                class="w-full">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </x-slot:icon>
                            </x-ui.input>
                        </div>
                        <div class="space-y-3">
                            <x-ui.label value="Telefone" class="text-zinc-500 ml-1" />
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

                <!-- Dropzone Placeholder -->
                <div
                    class="bg-zinc-900/50 border border-dashed border-zinc-700 rounded-2xl h-40 flex flex-col items-center justify-center text-zinc-500 gap-4 group cursor-pointer hover:border-primary-variant/50 hover:bg-zinc-800/50 transition-all duration-300">
                    <div
                        class="bg-zinc-800 p-3 rounded-full group-hover:bg-primary-variant group-hover:text-white transition-all shadow-lg">
                        <svg class="w-6 h-6 rotate-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-sm font-black text-zinc-100">Anexar Fotos do Problema</span>
                        <span class="text-[10px] uppercase tracking-wider opacity-60">Arraste aqui ou clique para
                            selecionar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer do Form Buttons -->
        <div
            class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-zinc-800/50 pt-8">
            <button type="button" @click="step > 1 ? step-- : step = 0"
                class="w-full sm:w-auto px-10 py-3.5 rounded-xl border border-zinc-800 text-zinc-400 font-bold hover:bg-zinc-800 hover:text-white transition-all active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar
            </button>

            <x-ui.button x-show="step < 3" @click="step++" class="w-full sm:w-auto px-12 py-3.5">
                <span class="flex items-center gap-2">
                    Avançar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </x-ui.button>

            <x-ui.button x-show="step === 3" @click="loading = true" class="w-full sm:w-auto px-12 py-3.5">
                <span x-show="!loading" class="flex items-center gap-2">
                    Finalizar Relato
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <x-ui.spinner class="w-4 h-4 text-white" />
                    Enviando...
                </span>
            </x-ui.button>
        </div>
    </x-ui.card>
</div>
