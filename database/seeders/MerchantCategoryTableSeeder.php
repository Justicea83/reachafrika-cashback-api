<?php

namespace Database\Seeders;

use App\Models\Category\MerchantCategory;
use Illuminate\Database\Seeder;

class MerchantCategoryTableSeeder extends Seeder
{
    private array $data  = [
        'restaurants',
        'shops'
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(MerchantCategory::query()->count() <= 0){
            foreach ($this->data as $item){
                MerchantCategory::query()->create([
                    'name' => $item
                ]);
            }
        }
    }
}
