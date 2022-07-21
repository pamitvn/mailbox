<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableDoesntHaveColumn('recharge_histories', 'extras', function (Blueprint $table) {
            $table->json('extras')->nullable()->after('note');
        });
    }

    public function down()
    {
        Schema::whenTableHasColumn('recharge_histories', 'extras', function (Blueprint $table) {
            $table->dropColumn('extras');
        });
    }
};
