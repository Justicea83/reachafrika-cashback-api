<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (State::query()->count() > 0)
            return;


        $states = json_decode(file_get_contents(public_path() . '/json/states.json'), true);

        foreach ($states as $state) {
            [
                'id' => $id,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'type' => $type,
                'state_code' => $state_code,
                'country_code' => $country_code,
                'country_id' => $country_id,
                'name' => $name
            ] = $state;
            $stateToCreate = [
                'id' => $id,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'type' => $type,
                'state_code' => $state_code,
                'country_code' => $country_code,
                'country_id' => $country_id,
                'name' => $name
            ];

            State::query()->firstOrCreate($stateToCreate);
        }
    }
}
