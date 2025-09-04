<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('standings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('driver_id')->constrained('drivers');
    $table->foreignId('constructor_id')->constrained('constructors');
    $table->string('season');
    $table->string('round');
    $table->integer('position');
    $table->integer('points');
    $table->integer('wins');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
