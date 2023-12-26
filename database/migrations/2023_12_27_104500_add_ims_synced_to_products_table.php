<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'ims_synced_at')) {
                $table->dateTime('ims_synced_at')->nullable()->after('status');
                $table->bigInteger('ims_enrolment_id')->nullable()->after('status');
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
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'ims_synced_at')) {
                $table->dropColumn('ims_synced_at');
            }
        });
    }
};
