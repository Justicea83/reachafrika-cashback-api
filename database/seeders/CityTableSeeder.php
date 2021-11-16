<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (City::query()->count() > 0)
            return;

        $cities = json_decode(file_get_contents(public_path() . '/json/cities.json'), true);

        foreach ($cities as $city){
            [
                'id' => $id,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'state_code' => $state_code,
                'country_code' => $country_code,
                'country_id' => $country_id,
                'wikiDataId' => $wikiDataId,
                'state_id' => $state_id,
                'name' => $name
            ] = $city;
            $cityToCreate = [
                'id' => $id,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'state_code' => $state_code,
                'country_code' => $country_code,
                'country_id' => $country_id,
                'wikiDataId' => $wikiDataId,
                'state_id' => $state_id,
                'name' => $name
            ];

            City::query()->firstOrCreate($cityToCreate);
        }
    }
}
