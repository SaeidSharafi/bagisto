<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attribute_options')->delete();

        DB::table('attribute_option_translations')->delete();

        DB::table('attribute_options')->insert([
            [
                'id'           => '1',
                'admin_name'   => 'مختلط',
                'sort_order'   => '1',
                'attribute_id' => '27',
            ], [
                'id'           => '2',
                'admin_name'   => 'بانوان',
                'sort_order'   => '2',
                'attribute_id' => '27',
            ], [
                'id'           => '3',
                'admin_name'   => 'آقایان',
                'sort_order'   => '3',
                'attribute_id' => '27',
            ], [
                'id'           => '4',
                'admin_name'   => 'شنبه',
                'sort_order'   => '1',
                'attribute_id' => '28',
            ], [
                'id'           => '5',
                'admin_name'   => 'یکشنبه',
                'sort_order'   => '2',
                'attribute_id' => '28',
            ], [
                'id'           => '6',
                'admin_name'   => 'دوشنبه',
                'sort_order'   => '3',
                'attribute_id' => '28',
            ], [
                'id'           => '7',
                'admin_name'   => 'سه‌شنبه',
                'sort_order'   => '4',
                'attribute_id' => '28',
            ], [
                'id'           => '8',
                'admin_name'   => 'چهارشنبه',
                'sort_order'   => '5',
                'attribute_id' => '28',
            ], [
                'id'           => '9',
                'admin_name'   => 'پنجشنبه',
                'sort_order'   => '6',
                'attribute_id' => '28',
            ], [
                'id'           => '10',
                'admin_name'   => 'جمعه',
                'sort_order'   => '7',
                'attribute_id' => '28',
            ], [
                'id'           => '11',
                'admin_name'   => 'مجازی',
                'sort_order'   => '1',
                'attribute_id' => '29',
            ], [
                'id'           => '12',
                'admin_name'   => 'اشتغالی',
                'sort_order'   => '2',
                'attribute_id' => '29',
            ], [
                'id'           => '13',
                'admin_name'   => 'مرکز ۱',
                'sort_order'   => '1',
                'attribute_id' => '30',
            ], [
                'id'           => '14',
                'admin_name'   => 'مرکز ۲',
                'sort_order'   => '2',
                'attribute_id' => '30',
            ]
        ]);

        DB::table('attribute_option_translations')->insert([
            [
                'id'                  => '1',
                'locale'              => 'fa',
                'label'               => 'مختلط',
                'attribute_option_id' => '1',
            ],[
                'id'                  => '2',
                'locale'              => 'fa',
                'label'               => 'بانوان',
                'attribute_option_id' => '2',
            ],[
                'id'                  => '3',
                'locale'              => 'fa',
                'label'               => 'آقایان',
                'attribute_option_id' => '3',
            ],[
                'id'                  => '4',
                'locale'              => 'fa',
                'label'               => 'شنبه',
                'attribute_option_id' => '4',
            ],[
                'id'                  => '5',
                'locale'              => 'fa',
                'label'               => 'یکشنبه',
                'attribute_option_id' => '5',
            ],[
                'id'                  => '6',
                'locale'              => 'fa',
                'label'               => 'دوشنبه',
                'attribute_option_id' => '6',
            ],[
                'id'                  => '7',
                'locale'              => 'fa',
                'label'               => 'سه‌شنبه',
                'attribute_option_id' => '7',
            ],[
                'id'                  => '8',
                'locale'              => 'fa',
                'label'               => 'چهارشنبه',
                'attribute_option_id' => '8',
            ],[
                'id'                  => '9',
                'locale'              => 'fa',
                'label'               => 'پنجشنبه',
                'attribute_option_id' => '9',
            ],[
                'id'                  => '10',
                'locale'              => 'fa',
                'label'               => 'جمعه',
                'attribute_option_id' => '10',
            ],[
                'id'                  => '11',
                'locale'              => 'fa',
                'label'               => 'مجازی',
                'attribute_option_id' => '11',
            ],[
                'id'                  => '12',
                'locale'              => 'fa',
                'label'               => 'اشتغالی',
                'attribute_option_id' => '12',
            ],[
                'id'                  => '13',
                'locale'              => 'fa',
                'label'               => 'مرکز ۱',
                'attribute_option_id' => '13',
            ],[
                'id'                  => '14',
                'locale'              => 'fa',
                'label'               => 'مرکز ۲',
                'attribute_option_id' => '14',
            ]
        ]);
    }
}
