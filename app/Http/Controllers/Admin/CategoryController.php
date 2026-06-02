<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Category::withCount('tickets')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->filled('active'), fn ($q) => $q->where('is_active', $request->active));

        $categories = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parents = Category::whereNull('parent_id')->where('is_active', true)->orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(Category $category): View
    {
        $parents = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->tickets()->exists()) {
            return back()->withErrors(['category' => 'Não é possível excluir uma categoria com ocorrências vinculadas. Desative-a.']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria removida.');
    }

    public function toggleActive(Category $category): RedirectResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        $msg = $category->is_active ? 'Categoria ativada.' : 'Categoria desativada.';
        return back()->with('success', $msg);
    }
}
