<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::all());
    }
    public function indexDeleted(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::onlyTrashed()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryStoreRequest $request
     * @return CategoryResource
     */
    public function store(CategoryStoreRequest $request): CategoryResource
    {
        return (new CategoryResource(Category::create($request->all())))->additional(['message'=>'Categoria Registrada']);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }
    public function showDeleted($name): CategoryResource
    {
        $category = Category::onlyTrashed()->where('name',$name)->firstOrFail();
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryUpdateRequest $request
     * @param Category $category
     * @return CategoryResource
     */
    public function update(CategoryUpdateRequest $request, Category $category): CategoryResource
    {
        $category->update($request->all());
        return (new CategoryResource($category))->additional(['message'=>'Categoria Actualizada']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return CategoryResource
     */
    public function destroy(Category $category): CategoryResource
    {
        $category->delete();
        return (new CategoryResource($category))->additional(['message'=>'Categoria Eliminada']);
    }
    public function restore($name): CategoryResource
    {
        $category = Category::onlyTrashed()->where('name',$name)->firstOrFail();
        $category->restore();
        return (new CategoryResource($category))->additional(['message'=>'Categoria Restaurada']);
    }
}
