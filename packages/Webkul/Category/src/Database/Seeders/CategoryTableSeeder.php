<?php

namespace Webkul\Category\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/*
 * Category table seeder.
 *
 * Command: php artisan db:seed --class=Webkul\\Category\\Database\\Seeders\\CategoryTableSeeder
 */
class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->delete();

        DB::table('category_translations')->delete();

        $now = Carbon::now();

        DB::table('categories')->insert([
            [
                'id'         => '1',
                'position'   => '1',
                'image'      => NULL,
                'status'     => '1',
                '_lft'       => '1',
                '_rgt'       => '14',
                'parent_id'  => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        DB::table('category_translations')->insert([
            [
                'name'             => 'Root',
                'slug'             => 'root',
                'description'      => 'Root',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '1',
                'locale'           => 'en',
            ],
            [
                'name'             => 'طبقه بندی دوره ها',
                'slug'             => 'root',
                'description'      => 'طبقه بندی دوره ها',
                'meta_title'       => '',
                'meta_description' => '',
                'meta_keywords'    => '',
                'category_id'      => '1',
                'locale'           => 'fa',
            ]
        ]);
    }
}
