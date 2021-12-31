<?php

namespace Database\Seeders;

use App\Models\Finance\Account;
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
        Branch::factory(100)->create();

        foreach(Merchant::with('branches')->get() as $merchant){
            Account::factory()->for($merchant)->create();
            if($merchant->main_branch_id == null){
                $merchant->main_branch_id = $merchant->branches->first()->id;
                $merchant->save();
            }
        }
    }
}
