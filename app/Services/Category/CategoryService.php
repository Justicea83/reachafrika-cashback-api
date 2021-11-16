<?php

namespace App\Services\Category;

use App\Models\Category\Category;
use App\Models\Category\MerchantCategory;
use App\Utils\CategoryUtils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class CategoryService implements ICategoryService
{

    public function getCategories(string $type): Collection
    {
        return $this->resolveCategoryType($type)->query()->get()->map(fn($category) => $this->map($category));
    }

    public function getDeletedCategories(string $type): Collection
    {
        return $this->resolveCategoryType($type)->query()->onlyTrashed()->get()->map(fn($category) => $this->map($category));
    }

    public function getCategory(string $type, int $id): array
    {
        $data = $this->resolveCategoryType($type)->query()->with('parent')->find($id);
        return $this->map($data);
    }

    public function getCategoryGroups(string $type): Collection
    {
        return $this->resolveCategoryType($type)->query()->with('children')
            ->whereNull('parent_id')
            ->get()
            ->map(fn($category) => $this->map($category));
    }

    public function createCategory(array $payload, string $type): array
    {
        $parentId = $payload['parent_id'] ?? null;
        $data = $payload;
        $data['parent_id'] = $parentId;
        return $this->map($this->resolveCategoryType($type)->query()->create($data));
    }

    private function resolveCategoryType(string $type): Model
    {
        switch ($type) {
            case CategoryUtils::MERCHANT_CATEGORY:
                return App::make(MerchantCategory::class);
        }
        throw new InvalidArgumentException("$type is not allowed");
    }


    /**
     * @param Category|Model $model
     * @return array
     */
    private function map(Category $model): array
    {
        $data = [
            'id' => $model->id,
            'name' => $model->name,
            'children' => $this->mapChildren($model->children)
        ];

        if ($model->parent != null) {
            $data['parent'] = $this->mapAlone($model->parent);
        }

        return $data;
    }

    private function mapChildren(Collection $data): Collection
    {
        return $data->map(fn($item) => $this->map($item));
    }

    private function mapAlone(Category $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
        ];
    }

    public function deleteCategory(string $type, int $id)
    {
        $data = $this->resolveCategoryType($type)->query()->find($id);
        if ($data != null)
            $data->delete();
    }

    public function unDeleteCategory(string $type, int $id): array
    {
        $this->resolveCategoryType($type)->query()->withTrashed()->find($id)->restore();
        return $this->getCategory($type, $id);
    }


    public function findRootCategoryByName(string $type, string $name): Model
    {
        return $this->resolveCategoryType($type)->query()
            ->where('name', $name)
            ->whereNull('parent_id')
            ->first();
    }

    public function getCategoryByParentId(string $type, int $parentId): Collection
    {
        return $this->resolveCategoryType($type)->query()->where('parent_id',$parentId)->get();
    }
}
