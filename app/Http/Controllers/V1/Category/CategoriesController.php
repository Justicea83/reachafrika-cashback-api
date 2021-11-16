<?php

namespace App\Http\Controllers\V1\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\GetCategoryRequest;
use App\Services\Category\ICategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CategoriesController extends Controller
{
    private ICategoryService $categoryService;

    function __construct(ICategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function get(GetCategoryRequest $request, string $type): JsonResponse
    {
        return $this->successResponse($this->categoryService->getCategories($type));
    }

    public function getDeleted(GetCategoryRequest $request, string $type): JsonResponse
    {
        return $this->successResponse($this->categoryService->getDeletedCategories($type));
    }

    public function getCategory(GetCategoryRequest $request, string $type,int $id): JsonResponse
    {
        return $this->successResponse($this->categoryService->getCategory($type,$id));
    }

    public function unDeleteCategory(GetCategoryRequest $request, string $type,int $id): JsonResponse
    {
        return $this->successResponse($this->categoryService->unDeleteCategory($type,$id));
    }

    public function deleteCategory(GetCategoryRequest $request, string $type,int $id): Response
    {
        $this->categoryService->deleteCategory($type,$id);
        return $this->noContent();
    }

    public function getGroups(GetCategoryRequest $request, string $type): JsonResponse
    {
        return $this->successResponse($this->categoryService->getCategoryGroups($type));
    }

    public function create(CreateCategoryRequest $request, string $type): JsonResponse
    {
        return $this->successResponse($this->categoryService->createCategory($request->all(), $type));
    }
}
