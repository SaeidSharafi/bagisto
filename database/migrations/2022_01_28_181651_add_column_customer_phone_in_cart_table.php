<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCustomerPhoneInCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart', function (Blueprint $table) {
            if (!Schema::hasColumn('cart','customer_phone')){
                $table->string('customer_phone')->after('customer_email')->nullable();
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
        Schema::table('cart', function (Blueprint $table) {
            if (Schema::hasColumn('cart','customer_phone')){
                $table->dropColumn('customer_phone');
            }
        });
    }
}
