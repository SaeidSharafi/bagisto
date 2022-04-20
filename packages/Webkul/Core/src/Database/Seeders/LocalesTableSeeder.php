<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class LocalesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('locales')->delete();

        DB::table('locales')->insert([
            [
                'id'   => 1,
                'code' => 'fa',
                'name' => 'فارسی',
                'direction' => 'rtl'
            ],
            [
                'id'   => 2,
                'code' => 'en',
                'name' => 'English',
            ]
        ]);
    }
}
