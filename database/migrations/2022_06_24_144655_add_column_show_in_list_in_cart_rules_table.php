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
        Schema::table('cart_rules', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_rules','show_in_list')){
                $table->tinyInteger('show_in_list')->after('status')->default(0);
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
        Schema::table('list_in_cart_rules', function (Blueprint $table) {
            if (Schema::hasColumn('cart_rules','show_in_list')){
                $table->dropColumn('show_in_list');
            }
        });
    }
};
