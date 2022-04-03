<?php

namespace Webkul\Inventory\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class InventoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('inventory_sources')->delete();

        DB::table('inventory_sources')->insert([
            'id'             => 1,
            'code'           => 'jedu',
            'name'           => 'جهاد دانشگاهی',
            'contact_name'   => 'جهاد دانشگاهی قزوین',
            'contact_email'  => 'info@jedu.ir',
            'contact_number' => '33376797-9(028)',
            'status'         => 1,
            'country'        => 'IR',
            'state'          => 'قزوین',
            'street'         => 'چهارراه ولیعصر(عج)',
            'city'           => 'قزوین',
            'postcode'       => '48127',
        ]);
    }
}