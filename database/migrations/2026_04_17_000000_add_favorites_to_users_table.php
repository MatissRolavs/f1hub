<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('favorite_constructor_id')
                ->nullable()
                ->after('role')
                ->constrained('constructors')
                ->nullOnDelete();
            $table->foreignId('favorite_driver_id')
                ->nullable()
                ->after('favorite_constructor_id')
                ->constrained('drivers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('favorite_driver_id');
            $table->dropConstrainedForeignId('favorite_constructor_id');
        });
    }
};
