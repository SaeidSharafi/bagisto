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
        Schema::create('moodle_enrolments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_national_code',10);
            $table->foreign('customer_national_code')->references('national_code')->on('customers')->onDelete('cascade');
            $table->bigInteger('moodle_course_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moodle_enrolments');
    }
};
