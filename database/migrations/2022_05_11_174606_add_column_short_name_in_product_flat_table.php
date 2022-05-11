<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if (!Schema::hasColumn('product_flat','short_name')){
                $table->string('short_name')->after('name');
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
        Schema::table('product_flat', function (Blueprint $table) {
            if (Schema::hasColumn('product_flat','short_name')){
                $table->dropColumn('short_name');
            }
        });
    }
};
