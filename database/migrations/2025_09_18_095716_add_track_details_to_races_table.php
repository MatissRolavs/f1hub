<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('races', function (Blueprint $table) {
            $table->string('track_image')->nullable()->after('url');   // URL to track layout image
            $table->string('track_length')->nullable()->after('track_image'); // e.g. "5.793 km"
            $table->integer('turns')->nullable()->after('track_length');      // number of turns
            $table->string('lap_record')->nullable()->after('turns');         // e.g. "1:18.149 - Lewis Hamilton (2020)"
            $table->text('description')->nullable()->after('lap_record');     // interesting facts / history
        });
    }

    public function down(): void
    {
        Schema::table('races', function (Blueprint $table) {
            $table->dropColumn(['track_image', 'track_length', 'turns', 'lap_record', 'description']);
        });
    }
};
