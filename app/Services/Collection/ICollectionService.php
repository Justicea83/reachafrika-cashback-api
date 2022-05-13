<?php

namespace App\Services\Collection;

use App\Models\User;
use Illuminate\Support\Collection;

interface ICollectionService
{
    public function loadCollection(string $type, array $payload): Collection;

    public function loadAuthCollection(User $user,string $type) : Collection;

    public function getCollectionTypes(): array;

    public function getAuthCollectionTypes(): array;
}
