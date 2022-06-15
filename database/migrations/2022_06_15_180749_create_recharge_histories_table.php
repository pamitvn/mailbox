<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('recharge_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unsigned()->constrained('users')->cascadeOnDelete();
            $table->foreignId('bank_id')->unsigned()->constrained('banks')->cascadeOnDelete();
            $table->string('amount', 191);
            $table->string('before_transaction', 191);
            $table->string('after_transaction', 191);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recharge_histories');
    }
};
