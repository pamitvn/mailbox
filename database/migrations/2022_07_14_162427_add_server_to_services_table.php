<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    static string $table = 'services';

    public function up()
    {
        Schema::whenTableDoesntHaveColumn(self::$table, 'is_local', fn(Blueprint $table) => $table->boolean('is_local')
            ->default(true)
            ->after('visible')
        );
        Schema::whenTableDoesntHaveColumn(self::$table, 'extras', fn(Blueprint $table) => $table->json('extras')
            ->nullable()
            ->after('is_local')
        );
    }

    public function down()
    {
        Schema::whenTableHasColumn(self::$table, 'is_local', fn(Blueprint $table) => $table->dropColumn('is_local'));
        Schema::whenTableHasColumn(self::$table, 'extras', fn(Blueprint $table) => $table->dropColumn('extras'));
    }
};
