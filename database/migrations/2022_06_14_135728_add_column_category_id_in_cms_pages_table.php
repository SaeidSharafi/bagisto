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
        Schema::table('cms_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('cms_pages','category_id')){
                $table->bigInteger('category_id')->unsigned()->nullable()->after('layout');
                $table->foreign('category_id')->references('id')->on('cms_categories')->onDelete('cascade');
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
        Schema::table('cms_pages', function (Blueprint $table) {
            if (Schema::hasColumn('cms_pages','category_id')){
                $table->dropColumn('category_id');
            }
        });
    }
};
