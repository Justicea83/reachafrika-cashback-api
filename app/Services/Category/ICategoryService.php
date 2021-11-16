<?php

namespace App\Services\Category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ICategoryService
{
    public function getCategories(string $type): Collection;

    public function getDeletedCategories(string $type): Collection;

    public function getCategory(string $type, int $id): array;

    public function deleteCategory(string $type, int $id);

    public function unDeleteCategory(string $type, int $id): array;

    public function getCategoryGroups(string $type): Collection;

    public function getCategoryByParentId(string $type, int $parentId): Collection;

    public function findRootCategoryByName(string $type,string $name): Model;

    public function createCategory(array $payload, string $type): array;
}
