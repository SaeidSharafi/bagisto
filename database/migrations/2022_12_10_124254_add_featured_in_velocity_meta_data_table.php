<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            if (!Schema::hasColumn('velocity_meta_data', 'special_id')) {
                $table->string('special_id')->nullable();
            }
            if (!Schema::hasColumn('velocity_meta_data', 'special_image')) {
                $table->string('special_image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            //
        });
    }
};
