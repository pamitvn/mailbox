<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug')->unique()->index();
            $table->string('feature_image')->nullable();
            $table->string('lifetime');
            $table->integer('price', false)->default(0)->index();
            $table->boolean('pop3')->default(false);
            $table->boolean('imap')->default(false);
            $table->boolean('visible')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
