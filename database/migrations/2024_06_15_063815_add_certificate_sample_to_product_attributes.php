<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $rouyesh_code_attrib = [
            'code'                => 'certificate_sample',
            'admin_name'          => 'نمونه گواهی‌نامه',
            'type'                => 'image',
            'validation'          => null,
            'position'            => '4',
            'is_required'         => '0',
            'is_unique'           => '0',
            'value_per_locale'    => '0',
            'value_per_channel'   => '0',
            'is_filterable'       => '0',
            'is_configurable'     => '0',
            'is_user_defined'     => '0',
            'is_visible_on_front' => '1',
            'use_in_flat'         => '0',
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
            'is_comparable'       => '0',
        ];

        $attrib_id = \Illuminate\Support\Facades\DB::table('attributes')->where('code', 'certificate_sample')
            ->first()?->id;
        if (!$attrib_id) {
            $attrib_id = \Illuminate\Support\Facades\DB::table('attributes')
                ->insertGetId($rouyesh_code_attrib);
            DB::table('attribute_translations')->insert([
                'locale'       => 'fa',
                'name'         => 'نمونه گواهی‌نامه',
                'attribute_id' => $attrib_id,
            ]);

            $groups = DB::table('attribute_groups')->where('code', 'course_detail')->get();
            $groups->each(function ($group) use ($attrib_id) {
                DB::table('attribute_group_mappings')->insert([
                    [
                        'attribute_id'       => $attrib_id,
                        'attribute_group_id' => $group->id,
                        'position'           => '2',
                    ]
                ]);
            });

        }

    }
};
