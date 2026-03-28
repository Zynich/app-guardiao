<x-guest-layout>
    <div class="flex-1 w-full flex flex-col items-center justify-center p-6 bg-gradient-to-b from-guardiao-dark to-black">
        <div class="mb-10 text-center flex flex-col items-center">
            <x-admin-shield-logo class="w-20 h-20 mb-6" />
            <h2 class="text-4xl font-extrabold text-white tracking-tight mb-2">Painel Administrativo</h2>
            <p class="text-zinc-400 text-lg font-medium opacity-80">Acesso Restrito ao Servidor</p>
        </div>

        <div class="w-full max-w-md">
            <div class="bg-guardiao-card border border-zinc-800/50 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden group mb-6">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-guardiao-brand-start to-guardiao-brand-end opacity-20 group-hover:opacity-100 transition-opacity"></div>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ loading: false, showPassword: false, showError: false }" x-init="if({{ $errors->any() ? 'true' : 'false' }}) { setTimeout(() => showError = true, 100) }" @submit="loading = true" novalidate>
                    @csrf

                    <!-- Animated Error Notification (Unified Fluid Expansion/Contraction) -->
                    @if ($errors->any())
                        <div 
                            x-show="showError"
                            x-init="setTimeout(() => showError = false, 5000)"
                            x-transition:enter="transition-all transform ease-out duration-700"
                            x-transition:enter-start="max-h-0 opacity-0 mb-0 -translate-y-4"
                            x-transition:enter-end="max-h-32 opacity-100 mb-8 translate-y-0"
                            x-transition:leave="transition-all transform ease-in-out duration-800"
                            x-transition:leave-start="max-h-32 opacity-100 mb-8 translate-y-0"
                            x-transition:leave-end="max-h-0 opacity-0 mb-0 -translate-y-4"
                            class="overflow-hidden w-full relative z-50 px-1"
                            x-cloak
                        >
                            <div class="bg-zinc-800/80 backdrop-blur-md border border-zinc-700/50 rounded-2xl p-5 pr-12 shadow-2xl flex items-center gap-4 relative">
                                <div class="bg-red-500 rounded-full p-1.5 shadow-lg shadow-red-500/40 shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-zinc-100 text-sm font-bold tracking-tight leading-tight">Usuário e/ou Senha incorretos</span>
                                    <span class="text-zinc-500 text-[10px] uppercase tracking-wider font-semibold mt-0.5">Tente novamente</span>
                                </div>
                                
                                <!-- Close Button -->
                                <button type="button" @click="showError = false" class="absolute top-5 right-5 text-zinc-600 hover:text-zinc-300 transition-colors pointer-events-auto">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Progress line (Counter) - Rounded & Inset for Premium feel -->
                                <div class="absolute bottom-1 left-0 right-0 mx-5 h-1 bg-gradient-to-r from-red-600 to-red-400 animate-progress-shrink origin-left rounded-full shadow-[0_0_10px_rgba(239,68,68,0.4)]"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('E-mail')" class="text-zinc-400 text-xs ml-1" />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-guardiao-brand-end transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <x-text-input 
                                id="email" 
                                class="block w-full pl-12" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                placeholder="servidor@pref.gov.br"
                                required autofocus 
                                autocomplete="username" 
                            />
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <x-input-label for="password" :value="__('Senha')" class="text-zinc-400 text-xs ml-1" />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-guardiao-brand-end transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <x-text-input 
                                id="password" 
                                class="block w-full pl-12 pr-12"
                                ::type="showPassword ? 'text' : 'password'"
                                name="password"
                                placeholder="••••••••"
                                value="{{ old('password') }}"
                                required 
                                autocomplete="current-password" 
                            />
                            <!-- Eye Toggle -->
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-zinc-500 hover:text-white transition-colors focus:outline-none">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="pt-4">
                        <x-primary-button>
                            Entrar no Sistema
                        </x-primary-button>
                    </div>
                </form>
            </div>
            
            <div class="w-full text-center text-zinc-700 text-[10px] tracking-widest uppercase py-2">
                Sistema Interno v1.0.0-MVP
            </div>
        </div>
    </div>
</x-guest-layout>
