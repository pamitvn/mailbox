<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableHasColumn('orders', 'product_id', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });
    }

    public function down()
    {
        Schema::whenTableHasColumn('orders', 'product_id', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });
    }
};
