<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableHasColumn('products', 'payload', function (Blueprint $table) {
            $table->dropColumn('payload');
        });
        Schema::whenTableDoesntHaveColumn('products', 'payload', function (Blueprint $table) {
            $table->string('payload', 768)
                ->unique()
                ->nullable(false);
        });
    }

    public function down()
    {
        Schema::whenTableHasColumn('products', 'payload', function (Blueprint $table) {
            $table->dropColumn('payload');
        });
    }
};
