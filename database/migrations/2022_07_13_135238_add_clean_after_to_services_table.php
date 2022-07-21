<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableDoesntHaveColumn('services', 'clean_after', function (Blueprint $table) {
            $table->integer('clean_after')
                ->default(4)
                ->comment('Auto Clean After N hours')
                ->after('visible');
        });
    }

    public function down()
    {
        Schema::whenTableHasColumn('services', 'clean_after', function (Blueprint $table) {
            $table->dropColumn('clean_after');
        });
    }
};
