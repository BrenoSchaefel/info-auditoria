<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ncm_classificacao_tributaria', function (Blueprint $table) {
            $table->id();

            // NCM
            $table->string('cod_ncm', 20)->index();
            $table->string('tipo_codigo', 10)->default('NCM');        // NCM ou NBS

            // Classificação tributária de origem
            $table->string('cod_class_trib', 20)->index();
            $table->string('nome_reduzido')->nullable();
            $table->string('cst', 10);

            // Condições do item no anexo
            $table->integer('nro_anexo')->nullable();
            $table->text('desc_condicao')->nullable();
            $table->text('desc_excecao')->nullable();
            $table->text('desc_item_anexo')->nullable();
            $table->text('desc_anexo')->nullable();
            $table->string('tipo_permissao', 30)->nullable();         // PERMITIDO, VEDADO, etc.

            // Vigência
            $table->timestamp('dth_ini_vig')->nullable();
            $table->timestamp('dth_fim_vig')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ncm_classificacao_tributaria');
    }
};
