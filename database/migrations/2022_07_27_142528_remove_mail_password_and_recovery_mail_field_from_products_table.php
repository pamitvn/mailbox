<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::whenTableHasColumn('products', 'mail', fn (Blueprint $table) => $table->dropColumn('mail'));
        Schema::whenTableHasColumn('products', 'password', fn (Blueprint $table) => $table->dropColumn('password'));
        Schema::whenTableHasColumn('products', 'recovery_mail', fn (Blueprint $table) => $table->dropColumn('recovery_mail'));
    }

    public function down()
    {
        Schema::whenTableHasColumn('products', 'mail', fn (Blueprint $table) => $table->dropColumn('mail'));
        Schema::whenTableHasColumn('products', 'password', fn (Blueprint $table) => $table->dropColumn('password'));
        Schema::whenTableHasColumn('products', 'recovery_mail', fn (Blueprint $table) => $table->dropColumn('recovery_mail'));
    }
};
