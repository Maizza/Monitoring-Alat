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
        Schema::create('maintenance_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Mekanik yang ngerjain [cite: 6]
            $table->foreignId('comment_id')->nullable()->constrained(); // Merujuk ke komen user mana (khusus Repair) [cite: 20]
            $table->enum('type', ['preventive', 'repair']); // Jenis kerjaan [cite: 19, 20]
            $table->text('description');
            $table->string('attachment')->nullable(); // Bukti foto/video [cite: 6]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_events');
    }
};
