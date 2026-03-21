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
        Schema::create('ticket_media', function (Blueprint $table) {
            $table->id();
            
            // Se o chamado for pro void, a foto vai junto (onDelete cascade)
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');        
            
            // Quem subiu? (pode ser null se for o cidadão no form público)
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->string('file_path'); 
            $table->string('file_type')->default('image/jpeg');   

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_media');
    }
};
