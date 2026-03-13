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
        Schema::create('maintenance_events', function (Blueprint $table) {

            $table->id();

            // Mekanik / user yang melakukan maintenance
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Comment yang berkaitan (khusus repair)
            $table->foreignId('comment_id')
                  ->nullable()
                  ->constrained('comments')
                  ->nullOnDelete();

            // Jenis maintenance
            $table->enum('type', ['preventive', 'repair']);

            // Deskripsi pekerjaan
            $table->text('description');

            // Bukti foto / video
            $table->string('attachment')->nullable();

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