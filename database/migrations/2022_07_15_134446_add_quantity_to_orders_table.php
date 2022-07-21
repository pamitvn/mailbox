<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableDoesntHaveColumn('orders', 'quantity', function (Blueprint $table) {
            $table->integer('quantity')->after('price');
        });
    }

    public function down()
    {
        Schema::whenTableHasColumn('orders', 'quantity', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
