<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Portal\TicketController as PortalTicketController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Portal Público (Cidadão) ────────────────────────────────────────────────

Route::get('/', function () {
    $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
    return view('portal.index', compact('categories'));
})->name('portal.index');

Route::post('/ocorrencias', [PortalTicketController::class, 'store'])
    ->middleware('throttle:10,60')
    ->name('portal.tickets.store');

Route::get('/protocolo', [PortalTicketController::class, 'track'])
    ->name('portal.track');

// ─── Painel Administrativo ───────────────────────────────────────────────────

Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'));

Route::prefix('admin')
    ->middleware(['auth', 'verified', 'active'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Ocorrências (todos os papéis autenticados)
        Route::resource('tickets', Admin\TicketController::class)
            ->only(['index', 'create', 'store', 'show']);

        Route::patch('tickets/{ticket}/status', [Admin\TicketController::class, 'updateStatus'])
            ->name('tickets.status');

        Route::post('tickets/{ticket}/comments', [Admin\TicketController::class, 'addComment'])
            ->name('tickets.comments');

        Route::patch('tickets/{ticket}/assign', [Admin\TicketController::class, 'assign'])
            ->name('tickets.assign');

        // Funcionários (admin only)
        Route::resource('users', Admin\UserController::class)
            ->except(['show'])
            ->middleware('role:admin');

        Route::patch('users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])
            ->name('users.toggle-active')
            ->middleware('role:admin');

        // Categorias (admin + despachante)
        Route::resource('categories', Admin\CategoryController::class)
            ->except(['show'])
            ->middleware('role:admin,despachante');

        Route::patch('categories/{category}/toggle-active', [Admin\CategoryController::class, 'toggleActive'])
            ->name('categories.toggle-active')
            ->middleware('role:admin,despachante');

        // Perfil do usuário logado
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

require __DIR__.'/auth.php';
