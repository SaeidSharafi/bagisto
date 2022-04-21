<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $now = Carbon::now();

        DB::table('core_config')->insert([
            [
                'code'         => 'customer.settings.sms.verification',
                'value'        => '1',
                'channel_code' => null,
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'sms.general.notifications.verification.status',
                'value'        => '1',
                'channel_code' => null,
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'sms.general.notifications.verification.pattern',
                'value'        => 'mdoe1j1587',
                'channel_code' => null,
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'sms.general.notifications.registration.status',
                'value'        => '1',
                'channel_code' => null,
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ]);

    }
}
