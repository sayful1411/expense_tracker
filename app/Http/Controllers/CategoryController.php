<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = auth()->user()->categories()->withCount('expenses')->orderBy('name')->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveCategoryRequest $request): RedirectResponse
    {
        $category = new Category;
        $category->user()->associate($request->user());
        $category->name = $request->validated('name');
        $category->save();

        return redirect()->route('categories.index')->with('status', __('Category added.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        abort_unless($category->user_id === auth()->id(), 404);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveCategoryRequest $request, Category $category): RedirectResponse
    {
        abort_unless($category->user_id === auth()->id(), 404);

        $category->name = $request->validated('name');
        $category->save();

        return redirect()->route('categories.index')->with('status', __('Category updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        abort_unless($category->user_id === auth()->id(), 404);

        if ($category->expenses()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', __('Categories with expenses cannot be deleted.'));
        }

        $category->delete();

        return redirect()->route('categories.index')->with('status', __('Category deleted.'));
    }
}
