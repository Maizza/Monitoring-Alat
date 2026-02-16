<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Siapa yang lapor [cite: 12]
            $table->foreignId('alat_id')->constrained('alats'); // Alat mana yang dilapor [cite: 4]
            $table->text('content')->nullable(); // Komentar tertulis 
            $table->string('voice_note')->nullable(); // Path file rekaman suara [cite: 9, 15]
            $table->string('photo')->nullable(); // Path foto [cite: 10, 16]
            $table->string('video')->nullable(); // Path video [cite: 10, 16]
            $table->string('unique_code'); // ID unik agar terlacak perbaikannya 
            $table->timestamps(); // Date & Time otomatis [cite: 12]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
