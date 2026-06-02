<x-admin-layout title="Funcionários" subtitle="Gerenciamento de usuários do sistema">
    <x-slot:actions>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary to-primary-variant rounded-xl text-xs font-black text-white uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-primary/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Funcionário
        </a>
    </x-slot:actions>

    <!-- Filtros -->
    <x-admin.section-card class="mb-5">
        <form method="GET" action="{{ route('admin.users.index') }}" class="p-4 grid grid-cols-1 sm:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar por nome ou e-mail..."
                   class="sm:col-span-2 bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm placeholder:text-zinc-600 focus:border-primary-variant focus:ring-primary-variant transition-all">

            <select name="role" class="bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all">
                <option value="">Todos os Papéis</option>
                @foreach($roles as $role)
                    <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                        {{ $role->label() }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-primary-variant/10 border border-primary-variant/30 text-primary-variant rounded-xl text-sm font-bold hover:bg-primary-variant/20 transition-all">
                    Filtrar
                </button>
                @if(request()->hasAny(['search','role','active']))
                    <a href="{{ route('admin.users.index') }}"
                       class="px-4 py-2.5 border border-zinc-700 text-zinc-400 rounded-xl text-sm font-bold hover:bg-zinc-800 transition-all">
                        Limpar
                    </a>
                @endif
            </div>
        </form>
    </x-admin.section-card>

    <x-admin.section-card>
        @if($users->isEmpty())
            <x-admin.empty-state title="Nenhum funcionário encontrado" description="Cadastre o primeiro funcionário." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-800/70">
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest">Nome</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest hidden md:table-cell">E-mail</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest">Papel</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest hidden lg:table-cell">Matrícula</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest hidden sm:table-cell">Último Acesso</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50">
                        @foreach($users as $user)
                            <tr class="hover:bg-zinc-800/20 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary-variant/20 border border-primary-variant/30 flex items-center justify-center text-primary-variant font-black text-xs shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-zinc-400 hidden md:table-cell">{{ $user->email }}</td>
                                <td class="px-5 py-3.5">
                                    <x-admin.badge :classes="$user->role->badgeClasses()">{{ $user->role->label() }}</x-admin.badge>
                                </td>
                                <td class="px-5 py-3.5 text-zinc-500 text-xs hidden lg:table-cell">
                                    {{ $user->badge_number ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-zinc-500 text-xs hidden sm:table-cell">
                                    {{ $user->last_login_at?->format('d/m/Y H:i') ?? 'Nunca' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($user->is_active)
                                        <x-admin.badge classes="bg-green-500/10 text-green-400 border-green-500/30">Ativo</x-admin.badge>
                                    @else
                                        <x-admin.badge classes="bg-zinc-500/10 text-zinc-500 border-zinc-500/30">Inativo</x-admin.badge>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2 justify-end">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="text-zinc-500 hover:text-primary-variant transition-colors p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-zinc-500 hover:text-yellow-400 transition-colors p-1"
                                                        title="{{ $user->is_active ? 'Desativar' : 'Ativar' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $user->is_active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  x-data onsubmit="return confirm('Excluir {{ addslashes($user->name) }}? Esta ação não pode ser desfeita.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-zinc-500 hover:text-red-400 transition-colors p-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-5 py-4 border-t border-zinc-800/70">
                    {{ $users->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @endif
    </x-admin.section-card>

</x-admin-layout>
