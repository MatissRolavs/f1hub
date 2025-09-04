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
        Schema::create('drivers', function (Blueprint $table) {
    $table->id(); // Laravel auto-incremented ID
    $table->string('driver_id')->unique(); // e.g. "piastri"
    $table->string('code')->nullable();
    $table->string('permanent_number')->nullable();
    $table->string('given_name');
    $table->string('family_name');
    $table->date('date_of_birth');
    $table->string('nationality');
    $table->string('url')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
