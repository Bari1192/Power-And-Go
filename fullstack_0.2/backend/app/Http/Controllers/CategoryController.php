<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(): JsonResource
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);
        return new CategoryResource($category);
    }
    public function show(Category $category): JsonResource
    {
        return new CategoryResource($category);
    }
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);
        return new CategoryResource($category);
    }

    public function destroy(Category $category): Response
    {
        return ($category->delete() ? response()->noContent() : abort(500));
    }
}
