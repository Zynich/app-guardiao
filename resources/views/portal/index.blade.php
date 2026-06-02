<x-guest-layout>

    <main x-data="portalApp" class="flex-1 w-full flex flex-col items-center justify-center py-12 bg-surface-darker">

        <!-- Hero Section (step 0) -->
        @include('portal.partials.hero')

        <!-- Tracking Form (step 0) -->
        @include('portal.partials.tracking')

        <!-- Multi-Step Form (steps 1, 2, 3) -->
        @include('portal.partials.step-form', ['categories' => $categories])

        <!-- Success (step 4) -->
        @include('portal.partials.success')

    </main>

    <footer class="w-full py-8 text-center text-zinc-600 text-sm bg-surface-darker">
        &copy; {{ date('Y') }} Guardião - Sistema de Gestão de Ocorrências
    </footer>

</x-guest-layout>
