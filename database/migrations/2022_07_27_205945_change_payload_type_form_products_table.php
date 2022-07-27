<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableHasColumn('products', 'payload', function (Blueprint $table) {
            $table->text('payload')
                ->nullable()
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
