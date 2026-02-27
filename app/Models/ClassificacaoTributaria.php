<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassificacaoTributaria extends Model
{
    protected $table = 'classificacao_tributaria';

    protected $fillable = [
        'cst',
        'descricao_cst',
        'codigo',
        'nome',
        'descricao',
        'redacao',
        'artigo',
        'tipo_aliquota',
        'aliq_red_ibs',
        'aliq_red_cbs',
        'ind_gtribregular',
        'ind_gcredpresoper',
        'ind_gmonopadrao',
        'ind_gmonoreten',
        'ind_gmonoret',
        'ind_gmonodif',
        'ind_gestornocred',
        'data_inicio_vigencia',
        'data_fim_vigencia',
        'data_atualizacao',
        'ind_nfeabi',
        'ind_nfe',
        'ind_nfce',
        'ind_cte',
        'ind_cteos',
        'ind_bpe',
        'ind_bpeta',
        'ind_bpetm',
        'ind_nfe3e',
        'ind_nfse',
        'ind_nfsevia',
        'ind_nfcom',
        'ind_nfag',
        'ind_nfgas',
        'ind_dere',
        'anexo',
        'link',
    ];

    protected $casts = [
        'data_inicio_vigencia' => 'date',
        'data_fim_vigencia'    => 'date',
        'data_atualizacao'     => 'date',
        'aliq_red_ibs'         => 'float',
        'aliq_red_cbs'         => 'float',
    ];

    // Mapeamento: cabeçalho CSV → coluna da tabela (na ordem do CSV)
    public static array $csvColunas = [
        'cst',
        'descricao_cst',
        'codigo',
        'nome',
        'descricao',
        'redacao',
        'artigo',
        'tipo_aliquota',
        'aliq_red_ibs',
        'aliq_red_cbs',
        'ind_gtribregular',
        'ind_gcredpresoper',
        'ind_gmonopadrao',
        'ind_gmonoreten',
        'ind_gmonoret',
        'ind_gmonodif',
        'ind_gestornocred',
        'data_inicio_vigencia',
        'data_fim_vigencia',
        'data_atualizacao',
        'ind_nfeabi',
        'ind_nfe',
        'ind_nfce',
        'ind_cte',
        'ind_cteos',
        'ind_bpe',
        'ind_bpeta',
        'ind_bpetm',
        'ind_nfe3e',
        'ind_nfse',
        'ind_nfsevia',
        'ind_nfcom',
        'ind_nfag',
        'ind_nfgas',
        'ind_dere',
        'anexo',
        'link',
    ];
}
