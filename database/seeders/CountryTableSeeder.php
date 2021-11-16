<?php

namespace Database\Seeders;

use App\Models\Misc\Country;
use Exception;
use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //codes
        if (Country::query()->count() > 0)
            return;


        $countries = json_decode(file_get_contents(public_path() . '/json/countries.json'), true);
        foreach ($countries as $country) {

            [
                'id' => $id,
                'subregion' => $subregion,
                'region' => $region,
                'native' => $native,
                'currency_symbol' => $currency_symbol,
                'currency' => $currency,
                'capital' => $capital,
                'phone_code' => $phone_code,
                'numeric_code' => $numeric_code,
                'iso2' => $iso2,
                'name' => $name,
                'iso3' => $iso3,
                'emojiU' => $emojiU,
                'emoji' => $emoji,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'tld' => $tld,
            ] = $country;

            $countryToCreate = [
                'id' => $id,
                'subregion' => $subregion,
                'region' => $region,
                'native' => $native,
                'currency_symbol' => $currency_symbol,
                'currency' => $currency,
                'capital' => $capital,
                'phone_code' => $phone_code,
                'numeric_code' => $numeric_code,
                'iso2' => $iso2,
                'name' => $name,
                'iso3' => $iso3,
                'emojiU' => $emojiU,
                'emoji' => $emoji,
                'tld' => $tld,
                'longitude' => $longitude,
                'latitude' => $latitude,
            ];

            Country::query()->firstOrCreate($countryToCreate);
        }


    }

}
