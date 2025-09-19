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
        Schema::create('race_results', function (Blueprint $table) {
            $table->id();
            $table->string('season');
            $table->string('round');
            $table->string('race_name');
            $table->date('date');
            $table->time('time')->nullable();
            $table->foreignId('driver_id')->constrained('drivers');
            $table->foreignId('constructor_id')->constrained('constructors');
            $table->integer('grid');
            $table->integer('position')->nullable();
            $table->string('position_text');
            $table->integer('points');
            $table->integer('laps');
            $table->string('status');
            $table->string('race_time')->nullable();
            $table->string('fastest_lap_time')->nullable();
            $table->integer('fastest_lap_rank')->nullable();
            $table->string('fastest_lap_speed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('race_results');
    }
};
