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
        Schema::create('preco_insercao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo_produto')->unique();
            $table->decimal('valor', 12, 2)->nullable();
            $table->string('moeda', 10)->nullable();
            $table->decimal('percentual_desconto', 5, 4)->nullable();
            $table->decimal('percentual_acrescimo', 5, 4)->nullable();
            $table->decimal('valor_promocional', 12, 2)->nullable();
            $table->date('data_inicio_promocao')->nullable();
            $table->date('data_fim_promocao')->nullable();
            $table->date('data_atualizacao')->nullable();
            $table->string('origem', 50)->nullable();
            $table->string('tipo_cliente', 30)->nullable();
            $table->string('vendedor_responsavel', 100)->nullable();
            $table->text('observacao')->nullable();
            $table->string('status', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preco_insercao');
    }
};
