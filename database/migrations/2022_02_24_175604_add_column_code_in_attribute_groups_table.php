<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCodeInAttributeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attribute_groups', function (Blueprint $table) {
            if(!Schema::hasColumn('attribute_groups','code')){
                $table->string('code')->after('id');
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
        Schema::table('attribute_groups', function (Blueprint $table) {
            if(Schema::hasColumn('attribute_groups','code')){
                $table->dropColumn('code');
            }
        });
    }
}
