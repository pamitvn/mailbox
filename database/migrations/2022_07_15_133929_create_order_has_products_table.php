<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('order_has_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->index()->unsigned()->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->index()->unique()->constrained('products')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_has_products');
    }
};
