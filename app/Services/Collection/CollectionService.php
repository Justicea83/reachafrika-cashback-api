<?php

namespace App\Services\Collection;

use App\Models\Category\MerchantCategory;
use App\Models\Finance\PaymentMode;
use App\Models\Misc\Country;
use App\Models\State;
use App\Utils\CollectionUtils;
use App\Utils\Core\BaseCoreService;
use App\Utils\Core\Endpoints;
use App\Utils\Finance\PaymentMode\PaymentModeUtils;
use App\Utils\SettlementBankUtils;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class CollectionService implements ICollectionService
{

    private Country $countryModel;
    private State $stateModel;
    private MerchantCategory $merchantCategoryModel;
    private PaymentMode $paymentModeModel;

    public function __construct(
        Country          $countryModel,
        MerchantCategory $merchantCategoryModel,
        State            $stateModel,
        PaymentMode      $paymentModeModel
    )
    {
        $this->countryModel = $countryModel;
        $this->merchantCategoryModel = $merchantCategoryModel;
        $this->stateModel = $stateModel;
        $this->paymentModeModel = $paymentModeModel;
    }

    /**
     * @throws RequestException
     */
    public function loadCollection(string $type, array $payload): Collection
    {
        switch ($type) {
            case CollectionUtils::COLLECTION_TYPE_CATEGORY:
                return $this->loadCategories();
            case CollectionUtils::COLLECTION_TYPE_COUNTRIES:
                return $this->loadCountries();
            case CollectionUtils::COLLECTION_TYPE_COUNTRIES_STATES:
                ['type_id' => $typeId] = $payload;
                return $this->loadCountryStates($typeId);
            case CollectionUtils::COLLECTION_TYPE_CAMPAIGNS:
                return collect(['video', 'flyer', 'rich_media', 'loops', 'survey']);
            case CollectionUtils::COLLECTION_TYPE_GENDER:
                return collect(['all', 'male', 'female', 'other']);
            case CollectionUtils::COLLECTION_TYPE_MARITAL_STATUS:
                return collect(['all', 'married', 'single']);
            case CollectionUtils::COLLECTION_TYPE_EDUCATION:
                return collect(['all', 'phd', 'mphil', 'degree', 'high School']);
            case CollectionUtils::COLLECTION_TYPE_MERCHANT_SETTLEMENT_BANK_PURPOSES:
                return collect(SettlementBankUtils::SETTLEMENT_PURPOSES);
            case CollectionUtils::COLLECTION_TYPE_MERCHANT_WITHDRAWAL_MODES:
                return $this->paymentModeModel->query()->whereIn('name', [PaymentModeUtils::PAYMENT_MODE_BANK, PaymentModeUtils::PAYMENT_MODE_MOMO])->get()->map(fn(PaymentMode $paymentMode) => [
                    'id' => $paymentMode->id,
                    'display_name' => $paymentMode->display_name
                ]);
            case CollectionUtils::COLLECTION_TYPE_INTERESTS:
            case CollectionUtils::COLLECTION_TYPE_PROFESSIONS:
            case CollectionUtils::COLLECTION_TYPE_LANGUAGES:
                $response = BaseCoreService::makeCall(Endpoints::getEndpointForAction(Endpoints::LISTS_ENDPOINT . $type));
                return collect($response->json());
            default:
                throw new InvalidArgumentException("the collection type cannot be found");
        }
    }

    private function loadCountries()
    {
        return $this->countryModel->query()->get();
    }

    private function loadCountryStates(int $countryId)
    {
        return $this->stateModel->query()->where('country_id', $countryId)->get();
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
