<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'pos',
                'description' => 'Manages Point of Sales',
                'children' => [
                    [
                        'name' => 'assign_pos',
                    ],
                    [
                        'name' => 'view_pos'
                    ],
                    [
                        'name' => 'create_pos'
                    ],
                    [
                        'name' => 'delete_pos'
                    ],
                    [
                        'name' => 'edit_pos'
                    ]
                ]
            ],
            [
                'name' => 'branches',
                'description' => 'Manages Branches',
                'children' => [
                    [
                        'name' => 'view_branches'
                    ],
                    [
                        'name' => 'create_branches'
                    ],
                    [
                        'name' => 'delete_branches'
                    ],
                    [
                        'name' => 'edit_branches'
                    ]
                ]
            ],
            [
                'name' => 'overview',
                'description' => 'Manages Dashboard Info',
                'children' => [
                    [
                        'name' => 'view_all'
                    ],
                    [
                        'name' => 'view_total_revenue'
                    ],
                    [
                        'name' => 'view_chart_information'
                    ],
                    [
                        'name' => 'view_total_transactions'
                    ],
                    [
                        'name' => 'view_pos_revenue'
                    ],
                    [
                        'name' => 'view_branches_revenue'
                    ],
                ]
            ],
            [
                'name' => 'transactions',
                'description' => 'Manages Transactions',
                'children' => [
                    [
                        'name' => 'view_all'
                    ]
                ]
            ],
            [
                'name' => 'promos',
                'description' => 'Manages Promo Campaigns',
                'children' => [
                    [
                        'name' => 'view_all'
                    ],
                    [
                        'name' => 'view_ongoing_campaigns'
                    ],
                    [
                        'name' => 'create_campaign'
                    ],
                    [
                        'name' => 'delete_campaign'
                    ],
                    [
                        'name' => 'view_campaign_stats'
                    ],
                    [
                        'name' => 'download_campaign_data'
                    ],
                ]
            ],
            [
                'name' => 'review',
                'description' => 'Manages Reviews',
                'children' => [
                    [
                        'name' => 'view_all'
                    ],
                    [
                        'name' => 'reply_comments'
                    ],
                ]
            ],
            [
                'name' => 'settlements',
                'description' => 'Manages Settlements',
                'children' => [
                    [
                        'name' => 'view_all'
                    ],
                ]
            ],
        ];

        foreach ($permissions as $permission){
            $parent = Permission::generateFor($permission['name'],null,$permission['description']);
            $children = $permission['children'];
            if(count($children) > 0){
                foreach ($children as $child){
                    Permission::generateFor($child['name'],$parent->id);
                }
            }
        }
    }
}
