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
                $table->dateTime('rouyesh_synced_at')->nullable()->after('ims_enrolment_id');
                $table->bigInteger('rouyesh_enrolment_id')->nullable()->after('rouyesh_synced_at');
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
            if (Schema::hasColumn('orders', 'rouyesh_synced_at')) {
                $table->dropColumn('rouyesh_synced_at');
            }
            if (Schema::hasColumn('orders', 'rouyesh_enrolment_id')) {
                $table->dropColumn('rouyesh_enrolment_id');
            }
        });
    }
};
