<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('attribute_groups')->delete();

        DB::table('attribute_group_mappings')->delete();

        DB::table('attribute_groups')->delete();

        DB::table('attribute_groups')->insert([
            [
                'id'                  => '1',
                'code'                => 'general',
                'name'                => 'عمومی',
                'position'            => '1',
                'is_user_defined'     => '0',
                'attribute_family_id' => '1',
            ], [
                'id'                  => '2',
                'code'                => 'description',
                'name'                => 'توضیحات',
                'position'            => '4',
                'is_user_defined'     => '0',
                'attribute_family_id' => '1',
            ], [
                'id'                  => '3',
                'code'                => 'meta_description',
                'name'                => 'توضیحات متا',
                'position'            => '6',
                'is_user_defined'     => '0',
                'attribute_family_id' => '1',
            ], [
                'id'                  => '4',
                'code'                => 'price',
                'name'                => 'قیمت',
                'position'            => '7',
                'is_user_defined'     => '0',
                'attribute_family_id' => '1',
            ], [
                'id'                  => '5',
                'code'                => 'shipping',
                'name'                => 'ارسال',
                'position'            => '8',
                'is_user_defined'     => '0',
                'attribute_family_id' => '1'
            ], [
                'id'                  => '6',
                'code'                => 'course_detail',
                'name'                => 'مشخصات دوره',
                'position'            => '2',
                'is_user_defined'     => '0',
                'attribute_family_id' => '1',
            ], [
                'id'                  => '7',
                'code'                => 'teacher_detail',
                'name'                => 'اطلاعات استاد',
                'position'            => '3',
                'is_user_defined'     => '0',
                'attribute_family_id' => '1',
            ], [
                'id'                  => '8',
                'code'                => 'course_desc',
                'name'                => 'ویژگی های بخش دوم',
                'position'            => '5',
                'is_user_defined'     => '0',
                'attribute_family_id' => '1',
            ]
        ]);

        DB::table('attribute_group_mappings')->insert([
            [
                'attribute_id'       => '1',
                'attribute_group_id' => '1',
                'position'           => '1',
            ], [
                'attribute_id'       => '2',
                'attribute_group_id' => '1',
                'position'           => '3',
            ], [
                'attribute_id'       => '3',
                'attribute_group_id' => '1',
                'position'           => '3',
            ], [
                'attribute_id'       => '4',
                'attribute_group_id' => '1',
                'position'           => '4',
            ], [
                'attribute_id'       => '5',
                'attribute_group_id' => '1',
                'position'           => '5',
            ], [
                'attribute_id'       => '6',
                'attribute_group_id' => '1',
                'position'           => '6',
            ], [
                'attribute_id'       => '7',
                'attribute_group_id' => '1',
                'position'           => '7',
            ], [
                'attribute_id'       => '8',
                'attribute_group_id' => '1',
                'position'           => '8',
            ], [
                'attribute_id'       => '9',
                'attribute_group_id' => '1',
                'position'           => '10',
            ], [
                'attribute_id'       => '10',
                'attribute_group_id' => '2',
                'position'           => '1',
            ], [
                'attribute_id'       => '11',
                'attribute_group_id' => '2',
                'position'           => '2',
            ], [
                'attribute_id'       => '12',
                'attribute_group_id' => '4',
                'position'           => '1',
            ], [
                'attribute_id'       => '13',
                'attribute_group_id' => '4',
                'position'           => '2',
            ], [
                'attribute_id'       => '14',
                'attribute_group_id' => '4',
                'position'           => '3',
            ], [
                'attribute_id'       => '15',
                'attribute_group_id' => '4',
                'position'           => '4',
            ], [
                'attribute_id'       => '16',
                'attribute_group_id' => '4',
                'position'           => '5',
            ], [
                'attribute_id'       => '17',
                'attribute_group_id' => '3',
                'position'           => '1',
            ], [
                'attribute_id'       => '18',
                'attribute_group_id' => '3',
                'position'           => '2',
            ], [
                'attribute_id'       => '19',
                'attribute_group_id' => '3',
                'position'           => '3',
            ], [
                'attribute_id'       => '20',
                'attribute_group_id' => '1',
                'position'           => '9',
            ], [
                'attribute_id'       => '21',
                'attribute_group_id' => '1',
                'position'           => '2',
            ], [
                'attribute_id'       => '22',
                'attribute_group_id' => '7',
                'position'           => '1',
            ], [
                'attribute_id'       => '23',
                'attribute_group_id' => '7',
                'position'           => '2',
            ], [
                'attribute_id'       => '24',
                'attribute_group_id' => '7',
                'position'           => '3',
            ], [
                'attribute_id'       => '25',
                'attribute_group_id' => '6',
                'position'           => '1',
            ], [
                'attribute_id'       => '26',
                'attribute_group_id' => '6',
                'position'           => '2',
            ], [
                'attribute_id'       => '27',
                'attribute_group_id' => '6',
                'position'           => '3',
            ], [
                'attribute_id'       => '28',
                'attribute_group_id' => '6',
                'position'           => '4',
            ], [
                'attribute_id'       => '29',
                'attribute_group_id' => '6',
                'position'           => '5',
            ], [
                'attribute_id'       => '30',
                'attribute_group_id' => '6',
                'position'           => '6',
            ], [
                'attribute_id'       => '31',
                'attribute_group_id' => '8',
                'position'           => '1',
            ], [
                'attribute_id'       => '32',
                'attribute_group_id' => '8',
                'position'           => '2',
            ], [
                'attribute_id'       => '33',
                'attribute_group_id' => '8',
                'position'           => '3',
            ], [
                'attribute_id'       => '34',
                'attribute_group_id' => '8',
                'position'           => '4',
            ], [
                'attribute_id'       => '35',
                'attribute_group_id' => '8',
                'position'           => '5',
            ], [
                'attribute_id'       => '36',
                'attribute_group_id' => '1',
                'position'           => '2',
            ], [
                'attribute_id'       => '37',
                'attribute_group_id' => '1',
                'position'           => '2',
            ], [
                'attribute_id'       => '38',
                'attribute_group_id' => '1',
                'position'           => '2',
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
}
