<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->when($request->filled('role'),   fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $term = "%{$request->search}%";
                $q->where('name', 'like', $term)->orWhere('email', 'like', $term);
            }))
            ->when($request->filled('active'), fn ($q) => $q->where('is_active', $request->active));

        $users = $query->orderBy('name')->paginate(20)->withQueryString();
        $roles = UserRole::cases();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = UserRole::cases();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'Funcionário cadastrado com sucesso.');
    }

    public function edit(User $user): View
    {
        $roles = UserRole::cases();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Funcionário atualizado com sucesso.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Você não pode excluir sua própria conta.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Funcionário removido com sucesso.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Você não pode desativar sua própria conta.']);
        }

        $user->update(['is_active' => ! $user->is_active]);

        $msg = $user->is_active ? 'Conta ativada.' : 'Conta desativada.';
        return back()->with('success', $msg);
    }
}
