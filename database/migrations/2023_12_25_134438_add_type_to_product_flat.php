<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if (!Schema::hasColumn('product_flat', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('product_flat', 'attribute_family_id')) {
                $table->integer('attribute_family_id')->unsigned()->nullable();
                $table->foreign('attribute_family_id')->references('id')->on('attribute_families')->onDelete('restrict');
            }

        });

    }

    public function down(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if (Schema::hasColumn('product_flat', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
