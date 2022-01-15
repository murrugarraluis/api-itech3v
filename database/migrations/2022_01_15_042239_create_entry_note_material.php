<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryNoteMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_note_material', function (Blueprint $table) {
            $table->foreignId('entry_note_id')->nullable()->constrained('entry_notes');
            $table->foreignId('material_id')->nullable()->constrained('materials');
            $table->string('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entry_note_material');
    }
}
