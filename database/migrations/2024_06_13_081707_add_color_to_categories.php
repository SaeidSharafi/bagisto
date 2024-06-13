<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'color')) {
                $table->string('color')->after('category_icon_path')->nullable();
            }
            if (!Schema::hasColumn('categories', 'is_carousel')) {
                $table->tinyInteger('is_carousel')->after('category_icon_path')->default(0)->nullable();
            }
        });
    }
};
