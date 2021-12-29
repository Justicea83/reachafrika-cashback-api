<?php

namespace Database\Seeders;

use App\Models\Merchant\Branch;
use App\Models\Merchant\Merchant;
use Illuminate\Database\Seeder;

class MerchantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::factory(500)->create();

        foreach(Merchant::with('branches')->get() as $merchant){
            if($merchant->main_branch_id == null){
                $merchant->main_branch_id = $merchant->branches->first()->id;
                $merchant->save();
            }
        }
    }
}
