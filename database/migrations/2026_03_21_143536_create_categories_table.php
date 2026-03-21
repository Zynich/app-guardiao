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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Auto-Relacionamento
            $table->foreignId('parent_id')->nullable()->constrained('categories'); // A tabela aponta pra ela mesma!
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('priority')->default('media'); // baixa, media, alta, urgente
            $table->integer('sla_hours')->default(72); // Tempo limite em horas pra resolver (ex: 72h)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
