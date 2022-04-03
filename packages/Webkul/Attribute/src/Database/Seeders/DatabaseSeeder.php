<?php

namespace Webkul\Attribute\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AttributeFamilyTableSeeder::class);
        $this->call(\Database\Seeders\AttributeGroupTableSeeder::class);
        $this->call(\Database\Seeders\AttributeTableSeeder::class);
        $this->call(\Database\Seeders\AttributeOptionTableSeeder::class);
    }
}
