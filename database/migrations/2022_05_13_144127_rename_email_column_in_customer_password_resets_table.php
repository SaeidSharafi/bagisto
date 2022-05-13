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
        Schema::table('customer_password_resets', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_password_resets','phone')){
                $table->string('phone')->fisrt()->nullable()->index();
            }
            if (Schema::hasColumn('customer_password_resets','email')){
                $table->string('email')->nullable()->change();
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
        Schema::table('customer_password_resets', function (Blueprint $table) {
            if (Schema::hasColumn('customer_password_resets','phone')){
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('customer_password_resets','email')){
                $table->string('email')->nullable(false)->change();
            }
        });
    }
};
