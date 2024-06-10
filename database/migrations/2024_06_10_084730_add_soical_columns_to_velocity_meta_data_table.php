<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            $table->string('telegram_url')->nullable()->after('subscription_bar_content');
            $table->string('instagram_url')->nullable()->after('subscription_bar_content');
            $table->string('aparat_url')->nullable()->after('subscription_bar_content');
            $table->string('bale_url')->nullable()->after('subscription_bar_content');
        });
    }

    public function down(): void
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            $table->dropColumn('telegram_url');
            $table->dropColumn('instagram_url');
            $table->dropColumn('aparat_url');
            $table->dropColumn('bale_url');
        });
    }
};
