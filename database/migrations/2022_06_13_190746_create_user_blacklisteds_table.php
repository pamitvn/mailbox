<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_blacklisted', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('by_user_id')->nullable()->unsigned()->constrained('users')->nullOnDelete();
            $table->string('reason', 150)->nullable();
            $table->timestamp('duration')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('user_blacklisted', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('by_user_id');
        });
        Schema::dropIfExists('user_blacklisted');
    }
};
