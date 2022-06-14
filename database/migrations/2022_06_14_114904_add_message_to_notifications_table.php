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
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications','message')){
                $table->text('message')->after('order_id');
            }
            if (Schema::hasColumn('notifications','order_id')){
                Schema::disableForeignKeyConstraints();
                $table->integer('order_id')->unsigned()->nullable(true)->change();
                Schema::enableForeignKeyConstraints();
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
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications','message')){
                $table->dropColumn('message');
            }
            if (Schema::hasColumn('notifications','order_id')){
                Schema::disableForeignKeyConstraints();
                $table->integer('order_id')->nullable(false)->change();
                Schema::enableForeignKeyConstraints();
            }
        });
    }
};
