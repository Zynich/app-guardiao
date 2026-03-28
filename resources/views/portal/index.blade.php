<x-guest-layout>

    <main x-data="{ 
        step: 0, 
        loading: false,
        formData: {
            category_id: '',
            description: '',
            address: '',
            reference_point: '',
            name: '',
            phone: '',
            email: '',
            cpf: ''
        }
    }" class="flex-1 w-full flex flex-col items-center justify-center py-12 bg-surface-darker">
        
        <!-- Hero Section -->
        @include('portal.partials.hero')

        <!-- Multi-Step Form -->
        @include('portal.partials.step-form', ['categories' => $categories])

        <!-- Tracking Section -->
        @include('portal.partials.tracking')

    </main>

    <footer class="w-full py-8 text-center text-zinc-600 text-sm bg-surface-darker">
        &copy; {{ date('Y') }} Guardião - Sistema de Gestão de Ocorrências
    </footer>
</x-guest-layout>
