<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if (!Schema::hasColumn('product_flat', 'rouyesh_code')) {
                $table->string('rouyesh_code')->nullable();
            }
        });

        $rouyesh_code_attrib = [
            'code'                => 'rouyesh_code',
            'admin_name'          => 'کد کلاس رویش',
            'type'                => 'text',
            'validation'          => null,
            'position'            => '3',
            'is_required'         => '0',
            'is_unique'           => '1',
            'value_per_locale'    => '0',
            'value_per_channel'   => '0',
            'is_filterable'       => '0',
            'is_configurable'     => '0',
            'is_user_defined'     => '0',
            'is_visible_on_front' => '0',
            'use_in_flat'         => '1',
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
            'is_comparable'       => '0',
        ];

        $rouyesh_code_attrib_id = \Illuminate\Support\Facades\DB::table('attributes')->where('code', 'rouyesh_code')
            ->first()?->id;
        if (!$rouyesh_code_attrib_id) {
            $rouyesh_code_attrib_id = \Illuminate\Support\Facades\DB::table('attributes')
                ->insertGetId($rouyesh_code_attrib);
            DB::table('attribute_translations')->insert([
                'locale'       => 'fa',
                'name'         => 'کد کلاس در رویش',
                'attribute_id' => $rouyesh_code_attrib_id,
            ]);

            $groups = DB::table('attribute_groups')->where('code', 'general')->get();
            $groups->each(function ($group) use ($rouyesh_code_attrib_id) {
                DB::table('attribute_group_mappings')->insert([
                    [
                        'attribute_id'       => $rouyesh_code_attrib_id,
                        'attribute_group_id' => $group->id,
                        'position'           => '2',
                    ]
                ]);
            });

        }

    }

    public function down(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if (Schema::hasColumn('product_flat', 'rouyesh_code')) {
                $table->dropColumn('rouyesh_code');
            }
        });
    }
};
