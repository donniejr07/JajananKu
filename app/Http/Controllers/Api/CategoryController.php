<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $cached = Cache::remember("categories:{$request->user()->id}", now()->addHour(), function () use ($request) {
                return $request->user()->categories()->orderBy('type')->orderBy('name')->get()->toArray();
            });

        return CategoryResource::collection(Category::hydrate($cached));
    }

    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = $request->user()->categories()->create($request->validated());

        return new CategoryResource($category);
    }

    public function show(Request $request, Category $category): CategoryResource
    {
        abort_unless($category->user_id === $request->user()->id, 404);

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        abort_unless($category->user_id === $request->user()->id, 404);

        $category->update($request->validated());

        return new CategoryResource($category);
    }

    public function destroy(Request $request, Category $category): JsonResponse
    {
        abort_unless($category->user_id === $request->user()->id, 404);

        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }
}
