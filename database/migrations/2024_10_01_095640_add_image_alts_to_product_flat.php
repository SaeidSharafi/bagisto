<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if(!Schema::hasColumn('product_flat','image_alts')){
                $table->text('image_alts')->nullable();
            }
        });
    }

};
