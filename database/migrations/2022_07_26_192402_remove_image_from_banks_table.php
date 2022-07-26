<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableHasColumn('banks', 'image', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    public function down()
    {
        Schema::whenTableHasColumn('banks', 'image', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
