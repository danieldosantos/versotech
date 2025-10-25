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
        Schema::create('produto_insercao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo')->unique();
            $table->string('nome');
            $table->string('categoria')->nullable();
            $table->string('subcategoria')->nullable();
            $table->text('descricao')->nullable();
            $table->string('fabricante')->nullable();
            $table->string('modelo')->nullable();
            $table->string('cor')->nullable();
            $table->decimal('peso_kg', 8, 3)->nullable();
            $table->decimal('largura_cm', 8, 2)->nullable();
            $table->decimal('altura_cm', 8, 2)->nullable();
            $table->decimal('profundidade_cm', 8, 2)->nullable();
            $table->string('unidade', 10)->nullable();
            $table->boolean('ativo')->default(true);
            $table->date('data_cadastro')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_insercao');
    }
};
