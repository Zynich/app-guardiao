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
        Schema::create('ticket_logs', function (Blueprint $table) {
            $table->id();

            // A âncora com o chamado pai
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            
            // Qual agente escreveu ou fez a ação?
            $table->foreignId('user_id')->nullable()->constrained('users');

            // A ação (Ex: 'status_alterado', 'comentario_adicionado')
            $table->string('action'); 

            // Rastro da auditoria (de -> para)
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();

            // O texto do comentário em si
            $table->text('comment')->nullable(); 

            // --- A MECÂNICA NOVA (O Escudo) ---
            // Define se o texto vai pro HUD do cidadão ou fica só no painel interno
            $table->boolean('is_public')->default(false);

            $table->timestamps();
            
            // A tua regra de "excluir se estiver errado" sem quebrar a rastreabilidade
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_logs');
    }
};
