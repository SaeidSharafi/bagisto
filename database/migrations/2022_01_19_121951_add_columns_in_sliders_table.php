<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (!Schema::hasColumn('sliders','title')){
                $table->string('title',100)->nullable();
            }
            if (!Schema::hasColumn('sliders','description')){
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('sliders','button')){
                $table->string('button',50)->nullable();
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
        Schema::table('sliders', function (Blueprint $table) {
            if (Schema::hasColumn('sliders','description')){
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('sliders','button')){
                $table->dropColumn('button');
            }
            if (!Schema::hasColumn('sliders','title')){
                $table->text('content')->nullable();
            }
        });
    }
}
