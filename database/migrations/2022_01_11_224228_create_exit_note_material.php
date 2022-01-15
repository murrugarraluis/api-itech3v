<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitNoteMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exit_note_material', function (Blueprint $table) {
            $table->foreignId('exit_note_id')->nullable()->constrained('exit_notes');
            $table->foreignId('material_id')->nullable()->constrained('materials');
            $table->string('quantity')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exit_note_material');
    }
}
