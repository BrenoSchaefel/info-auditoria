<?php

namespace App\Http\Controllers\Consulta;

use App\Http\Controllers\Controller;
use App\Models\NcmClassificacaoTributaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NcmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('consulta.ncm');
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'ncm' => ['required', 'string', 'max:20'],
        ]);

        // Normaliza: remove tudo que não for dígito
        $ncm = preg_replace('/[^0-9]/', '', trim($request->ncm));

        if (empty($ncm)) {
            return response()->json([
                'encontrado' => false,
                'mensagem'   => 'Informe um código NCM válido.',
            ], 422);
        }

        // DISTINCT ON (n.cod_ncm, n.cod_class_trib) garante uma única linha por
        // combinação NCM + classificação tributária, mesmo que a tabela tenha múltiplas
        // linhas com desc_item_anexo / desc_excecao diferentes.
        $sql = <<<SQL
            SELECT DISTINCT ON (n.cod_ncm, n.cod_class_trib)
                n.cod_ncm,
                n.tipo_codigo,
                n.cod_class_trib,
                n.nome_reduzido,
                n.cst,
                n.nro_anexo,
                n.desc_condicao,
                n.desc_excecao,
                n.desc_item_anexo,
                n.desc_anexo,
                n.tipo_permissao,
                n.dth_ini_vig,
                n.dth_fim_vig,
                c.descricao_cst,
                c.nome,
                c.descricao,
                c.tipo_aliquota,
                c.aliq_red_ibs,
                c.aliq_red_cbs,
                c.ind_nfe,
                c.ind_nfce,
                c.ind_cte,
                c.data_inicio_vigencia AS ct_data_inicio,
                c.data_fim_vigencia    AS ct_data_fim,
                c.link
            FROM ncm_classificacao_tributaria AS n
            LEFT JOIN classificacao_tributaria AS c
                ON c.codigo = n.cod_class_trib
               AND (c.ind_nfe = 1 OR c.ind_nfce = 1)
            WHERE n.cod_ncm        = ?
              AND n.tipo_permissao = 'PERMITIDO'
              AND (c.ind_nfe = 1 OR c.ind_nfce = 1)
            ORDER BY n.cod_ncm, n.cod_class_trib
        SQL;

        $resultados = collect(DB::select($sql, [$ncm]));

        if ($resultados->isEmpty()) {
            return response()->json([
                'encontrado' => false,
                'mensagem'   => "Nenhuma classificação tributária encontrada para o NCM <strong>{$ncm}</strong> com permissão PERMITIDO e aplicável a NF-e ou NFC-e.",
            ]);
        }

        return response()->json([
            'encontrado' => true,
            'ncm'        => $ncm,
            'total'      => $resultados->count(),
            'resultados' => $resultados,
        ]);
    }
}
