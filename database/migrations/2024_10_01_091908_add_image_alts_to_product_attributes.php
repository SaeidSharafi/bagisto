<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $rouyesh_code_attrib = [
            'code'                => 'image_alts',
            'admin_name'          => 'محتوای ALT تصاویر',
            'type'                => 'textarea',
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
            'use_in_flat'         => '1',
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
            'is_comparable'       => '0',
        ];

        $attrib_id = \Illuminate\Support\Facades\DB::table('attributes')->where('code', 'image_alts')
            ->first()?->id;
        if (!$attrib_id) {
            $attrib_id = \Illuminate\Support\Facades\DB::table('attributes')
                ->insertGetId($rouyesh_code_attrib);
            DB::table('attribute_translations')->insert([
                'locale'       => 'fa',
                'name'         => 'محتوای ALT تصاویر',
                'attribute_id' => $attrib_id,
            ]);

            $groups = DB::table('attribute_groups')->where('code', 'meta_description')->get();
            $groups->each(function ($group) use ($attrib_id) {
                DB::table('attribute_group_mappings')->insert([
                    [
                        'attribute_id'       => $attrib_id,
                        'attribute_group_id' => $group->id,
                        'position'           => '4',
                    ]
                ]);
            });

        }
    }

};
