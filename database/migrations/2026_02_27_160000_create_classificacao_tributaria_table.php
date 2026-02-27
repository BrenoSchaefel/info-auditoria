<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classificacao_tributaria', function (Blueprint $table) {
            $table->id();
            $table->string('cst', 3);
            $table->string('descricao_cst', 200);
            $table->string('codigo', 6);
            $table->string('nome', 200)->nullable();
            $table->text('descricao');
            $table->text('redacao')->nullable();
            $table->string('artigo', 100)->nullable();
            $table->string('tipo_aliquota', 100)->nullable();
            $table->decimal('aliq_red_ibs', 12, 2)->default(0);
            $table->decimal('aliq_red_cbs', 12, 2)->default(0);
            $table->integer('ind_gtribregular')->nullable();
            $table->integer('ind_gcredpresoper')->nullable();
            $table->integer('ind_gmonopadrao')->nullable();
            $table->integer('ind_gmonoreten')->nullable();
            $table->integer('ind_gmonoret')->nullable();
            $table->integer('ind_gmonodif')->nullable();
            $table->integer('ind_gestornocred')->nullable();
            $table->date('data_inicio_vigencia')->nullable();
            $table->date('data_fim_vigencia')->nullable();
            $table->date('data_atualizacao')->nullable();
            $table->integer('ind_nfeabi')->nullable();
            $table->integer('ind_nfe')->nullable();
            $table->integer('ind_nfce')->nullable();
            $table->integer('ind_cte')->nullable();
            $table->integer('ind_cteos')->nullable();
            $table->integer('ind_bpe')->nullable();
            $table->integer('ind_bpeta')->nullable();
            $table->integer('ind_bpetm')->nullable();
            $table->integer('ind_nfe3e')->nullable();
            $table->integer('ind_nfse')->nullable();
            $table->integer('ind_nfsevia')->nullable();
            $table->integer('ind_nfcom')->nullable();
            $table->integer('ind_nfag')->nullable();
            $table->integer('ind_nfgas')->nullable();
            $table->integer('ind_dere')->nullable();
            $table->string('anexo', 1000)->nullable();
            $table->string('link', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classificacao_tributaria');
    }
};
