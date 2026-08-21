<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $cached = Cache::tags([ReportService::CATEGORY_TAG, "user:{$request->user()->id}"])
            ->remember("categories:{$request->user()->id}", now()->addHour(), function () use ($request) {
                return $request->user()->categories()
                    ->withCount('transactions')
                    ->withSum('transactions as transactions_sum', 'amount')
                    ->orderBy('type')
                    ->orderBy('name')
                    ->get()
                    ->toArray();
            });

        return view('categories', [
            'categories' => Category::hydrate($cached),
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $request->user()->categories()->create($request->validated());

        return back()->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        abort_unless($category->user_id === $request->user()->id, 403);

        $category->update($request->validated());

        return back()->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        abort_unless($category->user_id === $request->user()->id, 403);

        $category->delete();

        return back()->with('status', 'Kategori berhasil dihapus.');
    }
}
