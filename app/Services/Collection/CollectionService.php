<?php

namespace App\Services\Collection;

use App\Models\Category\MerchantCategory;
use App\Models\Misc\Country;
use App\Utils\CollectionUtils;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class CollectionService implements ICollectionService
{

    private Country $countryModel;
    private MerchantCategory $merchantCategoryModel;

    public function __construct(Country $countryModel, MerchantCategory $merchantCategoryModel)
    {
        $this->countryModel = $countryModel;
        $this->merchantCategoryModel = $merchantCategoryModel;
    }

    public function loadCollection(string $type): Collection
    {
        switch ($type) {
            case CollectionUtils::COLLECTION_TYPE_CATEGORY:
                return $this->loadCategories();
            case CollectionUtils::COLLECTION_TYPE_COUNTRIES:
                return $this->loadCountries();
            default:
                throw new InvalidArgumentException("the collection type cannot be found");
        }
    }

    private function loadCountries()
    {
        return $this->countryModel->query()->get();
    }

    private function loadCategories()
    {
        return $this->merchantCategoryModel->query()->get();
    }

    public function getCollectionTypes(): array
    {
        return CollectionUtils::COLLECTION_TYPES;
    }
}
