<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            if (!Schema::hasColumn('velocity_meta_data','blog_title')){
                $table->string('blog_title')->nullable();
            }
            if (!Schema::hasColumn('velocity_meta_data','blog_url')){
                $table->string('blog_url')->nullable();
            }
            if (!Schema::hasColumn('velocity_meta_data','blog_image')){
                $table->string('blog_image')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            if (Schema::hasColumn('velocity_meta_data','blog_title')){
                $table->dropColumn('blog_title');
            }
            if (Schema::hasColumn('velocity_meta_data','blog_url')){
                $table->dropColumn('blog_url');
            }
            if (Schema::hasColumn('velocity_meta_data','blog_image')){
                $table->dropColumn('blog_image');
            }
        });
    }
};