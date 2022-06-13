<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_blacklisted', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->unsigned()->constrained(table_name_of_model(User::class))->cascadeOnDelete();
            $table->foreignId('by_user_id')->unique()->unsigned()->constrained(table_name_of_model(User::class))->nullOnDelete();
            $table->string('reason', 150)->nullable();
            $table->timestamp('duration')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_blacklisted');
    }
};
