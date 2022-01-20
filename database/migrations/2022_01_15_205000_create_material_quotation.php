<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialQuotation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_quotation', function (Blueprint $table) {
            $table->foreignId('quotation_id')->nullable()->constrained('quotations');
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
        Schema::dropIfExists('material_quotation');
    }
}
