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
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('custoemrs','father_name')){
                $table->string('father_name')->nullable();
            }
            if (!Schema::hasColumn('custoemrs','education_field')){
                $table->string('education_field')->nullable();
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
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('custoemrs','father_name')){
                $table->dropColumn('father_name');
            }
            if (Schema::hasColumn('custoemrs','education_field')){
                $table->dropColumn('education_field');
            }
        });
    }
};
