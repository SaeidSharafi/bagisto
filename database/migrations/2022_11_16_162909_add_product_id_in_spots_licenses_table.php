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
        Schema::table('spot_licenses', function (Blueprint $table) {
            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spot_licenses', function (Blueprint $table) {
            if (Schema::hasColumn('spot_licenses','product_id')){
                Schema::disableForeignKeyConstraints();
                $table->dropColumn('product_id');
                Schema::enableForeignKeyConstraints();
            }
        });
    }
};
