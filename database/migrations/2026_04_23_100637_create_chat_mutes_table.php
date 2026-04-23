<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_mutes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('muted_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('expires_at')->nullable(); // null = permanent
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_mutes');
    }
};
