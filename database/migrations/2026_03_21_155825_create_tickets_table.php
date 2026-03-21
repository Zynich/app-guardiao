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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // O Protocolo Público (Ex: 2026-ABC12)
            $table->string('protocol')->unique();

            // DADOS DO CIDADÃO
            $table->string('citizen_name'); // Obrigatório pra saber com quem falar
            $table->string('citizen_cpf')->nullable(); // Opcional pra não gerar atrito
            $table->string('citizen_email'); // Obrigatório: Pra mandar o protocolo e o link de confirmação
            $table->string('citizen_phone')->nullable(); // Opcional: PrO DESPACHANTE LIGAR SE PRECISAR

            // CHAVES ESTRANGEIRAS
            $table->foreignId('category_id')->constrained('categories'); 
            $table->foreignId('user_id')->nullable()->constrained('users'); // Agente Responsável
            $table->foreignId('created_by_id')->nullable()->constrained('users'); // Agente que criou (se não foi o cidadão)
            // STATUS pendente_triagem, em_andamento, resolvido, rejeitado
            $table->string('status')->default('pendente_triagem');
            $table->string('priority')->default('media');

            // DESCRIÇÃO DO PROBLEMA
            $table->text('description');

            // ENDEREÇO INCORPORADO
            $table->string('address');
            $table->string('reference_point')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // O SLA (Prazo limite calculado quando o chamado nasce)
            $table->timestamp('due_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
