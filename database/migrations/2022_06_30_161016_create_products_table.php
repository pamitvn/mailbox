<?php

use App\PAM\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->index()->constrained('services')->cascadeOnDelete();
            $table->string('payload', 768)
                ->unique()
                ->nullable(false);
            $table->integer('status')->default(ProductStatus::LIVE);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
