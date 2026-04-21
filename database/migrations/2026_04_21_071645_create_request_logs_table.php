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
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            // Relación polimórfica (vacation, paid, compensation)
            $table->morphs('loggable');

            // Usuario que hizo la acción
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Estado usando tu enum (se guarda como string)
            $table->string('status')->nullable();

            // Comentario opcional
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
