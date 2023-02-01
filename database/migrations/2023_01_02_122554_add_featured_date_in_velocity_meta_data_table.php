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
            if (!Schema::hasColumn('velocity_meta_data', 'special_from')) {
                $table->dateTime('special_from')->nullable();
            }
            if (!Schema::hasColumn('velocity_meta_data', 'special_to')) {
                $table->dateTime('special_to')->nullable();
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
