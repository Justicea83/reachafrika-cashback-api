<?php

namespace App\Utils\General;

class FilterOptions
{
    public int $page;
    public int $pageSize;
    public ?string $searchQuery;

    function __construct(int $page = 1, int $pageSize = 20, string $searchQuery = null)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->searchQuery = $searchQuery;
    }
}
