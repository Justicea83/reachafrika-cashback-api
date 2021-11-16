<?php

namespace App\Services\Settings\BusinessInfo;

use App\Models\Merchant\Merchant;
use App\Models\User;
use App\Services\Merchant\IMerchantService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class BusinessInfoService implements IBusinessInfoService
{
    private IMerchantService $merchantService;

    function __construct(IMerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
    }

    public function updateMerchantBusinessInfo(User $user, array $data, ?UploadedFile $avatar = null)
    {
        if ($user->merchant_id == null) return;
        //properties
        $name = Arr::get($data,'name');
        $categoryId = Arr::get($data,'category_id');
        $description = Arr::get($data,'description');
        $website = Arr::get($data,'website');
        //array values
        $headOffice = Arr::get($data, 'head_office');
        $socialMedia = Arr::get($data, 'social_media');

        /** @var Merchant $merchant */
        $merchant = $this->merchantService->getMerchant($user->merchant_id);

        if ($headOffice != null) {
            $this->updateHeadOfficeInformation($merchant, $headOffice);
        }

        if ($socialMedia != null) {
            $this->updateSocialMediaInformation($merchant, $socialMedia);
        }

        if($name != null)$merchant->name = $name;
        if($categoryId != null)$merchant->category_id = $categoryId;
        if($description != null)$merchant->about = $description;
        if($website != null)$merchant->website = $website;

        if ($avatar != null) {
            $path = Storage::disk('local-merchants')->putFile('avatars', $avatar);
            $merchant->avatar = $path;
        }


        if ($merchant->isDirty())
            $merchant->save();
    }

    private function updateHeadOfficeInformation(Merchant $merchant, array $payload)
    {
        $countryId = Arr::get($payload, 'country_id');
        $stateId = Arr::get($payload, 'state_id');
        $town = Arr::get($payload, 'town');
        $street = Arr::get($payload, 'street');
        $building = Arr::get($payload, 'building');
        $address = Arr::get($payload, 'address');

        if ($countryId != null) $merchant->head_office_country_id = $countryId;
        if ($stateId != null) $merchant->head_office_state_id = $stateId;
        if ($town != null) $merchant->head_office_city = $town;
        if ($street != null) $merchant->head_office_street = $street;
        if ($building != null) $merchant->head_office_building = $building;
        if ($address != null) $merchant->head_office_address = $address;
    }

    private function updateSocialMediaInformation(Merchant $merchant, array $payload)
    {
        if ($merchant->extra_data != null && isset($merchant->extra_data['social_media'])) {
            $extra_data = $merchant->extra_data;
            $data = json_decode($merchant->extra_data['social_media'],true);
            foreach ($payload as $key => $value) {
                $data[$key] = $value;
            }
            if (count($data) > 0) {
                $extra_data['social_media'] = json_encode($data);
                $merchant->extra_data = $extra_data;
            }
        } else {
            $data['social_media'] = json_encode($payload);
            $merchant->extra_data = $data;
        }
    }
}
