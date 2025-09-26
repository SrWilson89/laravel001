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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // ID del usuario que envía el mensaje (sender)
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            // ID del usuario que recibe el mensaje (receiver)
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('content'); // Contenido del mensaje
            $table->timestamp('read_at')->nullable(); // Para marcar si el mensaje ha sido leído
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};