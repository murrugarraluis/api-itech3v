<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_purchase', function (Blueprint $table) {
            $table->foreignId('purchase_id')->nullable()->constrained('purchases');
            $table->foreignId('material_id')->nullable()->constrained('materials');
            $table->string('quantity');
            $table->string('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_purchase');
    }
}
