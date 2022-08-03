<?php

use App\PAM\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('storage_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_id')->index()->unsigned()->constrained('storages')->cascadeOnDelete();
            $table->string('payload', 768)->unique();
            $table->integer('status')->default(ProductStatus::LIVE);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('storage_containers');
    }
};
