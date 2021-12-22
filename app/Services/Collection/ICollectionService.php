<?php

namespace App\Services\Collection;

use Illuminate\Support\Collection;

interface ICollectionService
{
    public function loadCollection(string $type,array $payload) : Collection;
    public function getCollectionTypes() : array;
}
