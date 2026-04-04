<?php

namespace App\Http\Controllers;

use App\Data\CategoryData;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        protected readonly CategoryService $service,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->service->getAll(),
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->service->create(
            CategoryData::fromArray($request->validated())
        );

        return response()->json([
            'success' => true,
            'data'    => $category,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->service->getById($id),
        ]);
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->service->update(
            $id,
            CategoryData::fromArray($request->validated())
        );

        return response()->json([
            'success' => true,
            'data'    => $category,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'data'    => null,
        ]);
    }
}
