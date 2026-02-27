<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NcmClassificacaoTributaria extends Model
{
    protected $table = 'ncm_classificacao_tributaria';

    protected $fillable = [
        'cod_ncm',
        'tipo_codigo',
        'cod_class_trib',
        'nome_reduzido',
        'cst',
        'nro_anexo',
        'desc_condicao',
        'desc_excecao',
        'desc_item_anexo',
        'desc_anexo',
        'tipo_permissao',
        'dth_ini_vig',
        'dth_fim_vig',
    ];

    protected $casts = [
        'dth_ini_vig' => 'datetime',
        'dth_fim_vig' => 'datetime',
    ];
}
