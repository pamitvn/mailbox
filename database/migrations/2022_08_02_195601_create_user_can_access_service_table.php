<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_can_access_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()
                ->unsigned()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('service_id')->index()
                ->unsigned()
                ->constrained('services')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_can_access_service');
    }
};
