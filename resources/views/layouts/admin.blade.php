<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Painel' }} — Guardião</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface-darker text-zinc-100" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden">

    <!-- ── Sidebar ──────────────────────────────────────────────── -->
    <aside
        class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 bg-surface border-r border-zinc-800/70 transition-transform duration-300 lg:static lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-zinc-800/70">
            <x-brand.admin-logo class="w-8 h-8 text-primary-variant" />
            <div>
                <p class="text-sm font-black text-white uppercase tracking-widest">Guardião</p>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">Painel Interno</p>
            </div>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" icon="chart-bar">
                Dashboard
            </x-admin.nav-link>

            <x-admin.nav-link :href="route('admin.tickets.index')" :active="request()->routeIs('admin.tickets.*')" icon="ticket">
                Ocorrências
            </x-admin.nav-link>

            @if(auth()->user()->hasRole(\App\Enums\UserRole::ADMIN, \App\Enums\UserRole::DESPACHANTE))
                <x-admin.nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" icon="tag">
                    Categorias
                </x-admin.nav-link>
            @endif

            @if(auth()->user()->isAdmin())
                <x-admin.nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" icon="users">
                    Funcionários
                </x-admin.nav-link>
            @endif

            <div class="pt-4 mt-4 border-t border-zinc-800/50">
                <x-admin.nav-link :href="route('portal.index')" icon="globe">
                    Ver Portal
                </x-admin.nav-link>
            </div>
        </nav>

        <!-- User Info -->
        <div class="px-4 py-4 border-t border-zinc-800/70">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-primary-variant/20 border border-primary-variant/40 flex items-center justify-center text-primary-variant font-black text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-zinc-500 font-bold">{{ auth()->user()->role->label() }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-zinc-500 hover:text-red-400 hover:bg-red-500/10 text-xs font-bold uppercase tracking-widest transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sair
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
         class="fixed inset-0 z-30 bg-black/60 lg:hidden"></div>

    <!-- ── Main Content ──────────────────────────────────────────── -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Topbar -->
        <header class="flex items-center justify-between h-16 px-6 border-b border-zinc-800/70 bg-surface shrink-0">
            <div class="flex items-center gap-4">
                <!-- Hamburger -->
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-zinc-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Page Title -->
                <div>
                    <h1 class="text-base font-black text-white uppercase tracking-tight">{{ $title ?? 'Painel' }}</h1>
                    @isset($subtitle)
                        <p class="text-xs text-zinc-500 font-medium">{{ $subtitle }}</p>
                    @endisset
                </div>
            </div>

            <!-- Header Actions Slot -->
            <div class="flex items-center gap-3">
                {{ $actions ?? '' }}
            </div>
        </header>

        <!-- Alerts (flash messages) -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mx-6 mt-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-green-500/10 border border-green-500/30 text-green-400 text-sm font-bold">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mx-6 mt-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-red-400 text-sm font-bold flex items-center gap-2">
                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>
