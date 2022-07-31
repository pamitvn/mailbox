<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableDoesntHaveColumn('products', 'payload', function (Blueprint $table) {
            $table->char('payload')
                ->unique()
                ->nullable(false)
                ->after('service_id');
        });
        Schema::whenTableHasColumn('products', 'payload', function (Blueprint $table) {
            $table->char('payload')
                ->unique()
                ->nullable(false)
                ->after('service_id')
                ->change();
        });
    }

    public function down()
    {
        Schema::whenTableHasColumn('products', 'payload', function (Blueprint $table) {
            $table->dropColumn('payload');
        });
    }
};
