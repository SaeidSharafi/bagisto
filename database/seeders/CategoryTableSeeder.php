<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('categories')->insert([
            [
                'id'         => '2',
                'position'   => '101',
                'image'      => null,
                'status'     => '1',
                '_lft'       => '15',
                '_rgt'       => '16',
                'parent_id'  => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '3',
                'position'   => '102',
                'image'      => null,
                'status'     => '1',
                '_lft'       => '17',
                '_rgt'       => '18',
                'parent_id'  => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => '4',
                'position'   => '103',
                'image'      => null,
                'status'     => '1',
                '_lft'       => '19',
                '_rgt'       => '20',
                'parent_id'  => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        DB::table('category_translations')->insert([
            [
                'name'             => 'مجازی',
                'slug'             => 'virtual',
                'description'      => 'دوره های مجازی',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '2',
                'locale'           => 'fa',
            ],
            [
                'name'             => 'اشتغال زا',
                'slug'             => 'career-generator',
                'description'      => 'دوره های اشتغال زا',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '3',
                'locale'           => 'fa',
            ],
            [
                'name'             => 'رایگان',
                'slug'             => 'free',
                'description'      => 'دوره های رایگان',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '4',
                'locale'           => 'fa',
            ],
        ]);
    }
}
