<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableDoesntHaveColumn('users', 'has_storage', function (Blueprint $table) {
            $table->boolean('has_storage')->default(false)->after('is_admin');
        });
    }

    public function down()
    {
        Schema::whenTableHasColumn('users', 'has_storage', function (Blueprint $table) {
            $table->dropColumn('has_storage');
        });
    }
};
