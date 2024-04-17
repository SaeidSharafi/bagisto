<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'rouyesh_code')) {
                $table->string('rouyesh_code')->nullable()->after('product_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
             if (Schema::hasColumn('order_items', 'rouyesh_code')) {
                $table->dropColumn('rouyesh_code');
            }
        });
    }
};
