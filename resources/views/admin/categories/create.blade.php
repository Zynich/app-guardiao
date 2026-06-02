<x-admin-layout title="Nova Categoria" subtitle="Criar tipo de ocorrência com SLA">
    <x-slot:actions>
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-zinc-700 text-zinc-400 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-zinc-800 transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Cancelar
        </a>
    </x-slot:actions>

    <div class="max-w-2xl">
        <x-admin.section-card title="Dados da Categoria">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="p-5 space-y-5">
                @csrf
                @include('admin.categories._form')
                <div class="flex gap-3 pt-4 border-t border-zinc-800/70">
                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-primary to-primary-variant rounded-xl font-black text-sm text-white uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-primary/30">
                        Criar Categoria
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-8 py-3 border border-zinc-700 text-zinc-400 rounded-xl text-sm font-bold hover:bg-zinc-800 hover:text-white transition-all">
                        Cancelar
                    </a>
                </div>
            </form>
        </x-admin.section-card>
    </div>

</x-admin-layout>
