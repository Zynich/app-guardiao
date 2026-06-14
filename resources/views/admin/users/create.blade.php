<x-admin-layout title="Novo Funcionário" subtitle="Cadastrar operador do sistema">
    <x-slot:actions>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-zinc-700 text-zinc-400 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-zinc-800 transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Cancelar
        </a>
    </x-slot:actions>

    <div class="max-w-2xl">
        <x-admin.section-card title="Dados do Funcionário">
            <form method="POST" action="{{ route('admin.users.store') }}" class="p-5 space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2 space-y-2">
                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Nome Completo *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                               class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">E-mail *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required maxlength="255"
                               class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Senha *</label>
                        <input type="password" name="password" required
                               class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('password') border-red-500 @enderror">
                        <p class="text-[10px] text-zinc-600">Mínimo 8 caracteres com letras e números.</p>
                        @error('password') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2" x-data="{
                        role: '{{ old('role') }}',
                        descriptions: {
                            'admin':        'Acesso total ao sistema: gerencia ocorrências, funcionários e categorias. Visualiza todos os dados do solicitante.',
                            'despachante':  'Gerencia e despacha ocorrências (criar, atualizar status, atribuir agentes, comentar). Acessa categorias. Não gerencia funcionários.',
                            'agente_campo': 'Acesso restrito às ocorrências atribuídas ao próprio agente. Não cria ocorrências nem acessa configurações.'
                        }
                    }">
                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Papel / Função *</label>
                        <select name="role" required x-model="role"
                                class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('role') border-red-500 @enderror">
                            <option value="">Selecione</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->value }}" {{ old('role') === $role->value ? 'selected' : '' }}>
                                    {{ $role->label() }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Descrição dinâmica de permissões -->
                        <div x-show="role && descriptions[role]" x-cloak
                             class="flex items-start gap-2 p-3 rounded-xl bg-primary-variant/5 border border-primary-variant/20 mt-1">
                            <svg class="w-3.5 h-3.5 text-primary-variant shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-xs text-zinc-400 leading-relaxed" x-text="descriptions[role]"></p>
                        </div>

                        @error('role') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Matrícula / Registro</label>
                        <input type="text" name="badge_number" value="{{ old('badge_number') }}" maxlength="20"
                               class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('badge_number') border-red-500 @enderror">
                        @error('badge_number') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2" x-data="inputMasks()">
                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">Telefone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" maxlength="15" inputmode="numeric"
                               @input="maskPhone($event)"
                               class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all">
                    </div>

                    <div class="space-y-2" x-data="inputMasks()">
                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest">CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf') }}" maxlength="14" inputmode="numeric"
                               @input="maskCpf($event)" @blur="validateCpf($event)"
                               class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all @error('cpf') border-red-500 @enderror">
                        @error('cpf') <p class="text-red-400 text-xs font-bold">{{ $message }}</p> @enderror
                        <p x-show="cpfError" x-text="cpfError" x-cloak class="text-red-400 text-xs font-bold"></p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="w-4 h-4 rounded border-zinc-600 bg-surface-darker text-primary-variant focus:ring-primary-variant">
                            <span class="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors">
                                Conta ativa (o funcionário poderá fazer login)
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-zinc-800/70">
                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-primary to-primary-variant rounded-xl font-black text-sm text-white uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-primary/30">
                        Cadastrar Funcionário
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="px-8 py-3 border border-zinc-700 text-zinc-400 rounded-xl text-sm font-bold hover:bg-zinc-800 hover:text-white transition-all">
                        Cancelar
                    </a>
                </div>
            </form>
        </x-admin.section-card>
    </div>

</x-admin-layout>
