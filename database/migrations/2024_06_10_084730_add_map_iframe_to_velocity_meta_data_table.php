<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            $table->text('map_iframe')->nullable()->after('subscription_bar_content');

        });
    }

    public function down(): void
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            $table->dropColumn('map_iframe');
        });
    }
};
