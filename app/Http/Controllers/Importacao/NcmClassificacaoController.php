<?php

namespace App\Http\Controllers\Importacao;

use App\Http\Controllers\Controller;
use App\Models\NcmClassificacaoTributaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NcmClassificacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $total  = NcmClassificacaoTributaria::count();
        $ultima = NcmClassificacaoTributaria::latest('updated_at')->value('updated_at');

        return view('importacao.ncm-classificacao.index', compact('total', 'ultima'));
    }

    public function importar(Request $request)
    {
        $request->validate([
            'arquivo' => ['required', 'file', 'mimes:json', 'max:20480'],
        ], [
            'arquivo.required' => 'Selecione um arquivo JSON.',
            'arquivo.mimes'    => 'O arquivo deve ser do tipo JSON.',
            'arquivo.max'      => 'O arquivo não pode ultrapassar 20 MB.',
        ]);

        $conteudo = file_get_contents($request->file('arquivo')->getRealPath());
        $dados    = json_decode($conteudo, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('erro', 'O arquivo JSON é inválido: ' . json_last_error_msg());
        }

        // Aceita tanto um array de classificações quanto um único objeto
        if (isset($dados['CodClassTrib'])) {
            $dados = [$dados];
        }

        if (!is_array($dados)) {
            return back()->with('erro', 'O arquivo JSON deve conter um array ou um objeto de classificação tributária.');
        }

        $registros = $this->extrairNcms($dados);

        DB::transaction(function () use ($registros) {
            NcmClassificacaoTributaria::truncate();

            foreach (array_chunk($registros, 500) as $lote) {
                NcmClassificacaoTributaria::insert($lote);
            }
        });

        $total = count($registros);
        $msg   = $total > 0
            ? "Importação concluída com sucesso! {$total} NCMs importados."
            : 'Importação concluída. Nenhum NCM com anexos encontrado no arquivo — a base foi limpa.';

        return back()->with('sucesso', $msg);
    }

    private function extrairNcms(array $dados): array
    {
        $registros = [];
        $agora     = now();

        // Estrutura: [ { Cst, ClassificacoesTributarias: [ { CodClassTrib, Anexos: [...] } ] } ]
        foreach ($dados as $cst) {
            if (empty($cst['ClassificacoesTributarias']) || !is_array($cst['ClassificacoesTributarias'])) {
                continue;
            }

            foreach ($cst['ClassificacoesTributarias'] as $classif) {
                $codClassTrib = $classif['CodClassTrib'] ?? null;
                $nomeReduzido = $classif['NomeReduzido'] ?? null;
                $cstCod       = $classif['Cst']          ?? null;

                if (empty($classif['Anexos']) || !is_array($classif['Anexos'])) {
                    continue;
                }

                foreach ($classif['Anexos'] as $anexo) {
                    if (($anexo['TipoCodigo'] ?? '') !== 'NCM') {
                        continue;
                    }

                    $codNcm = $anexo['CodNcmNbs'] ?? null;
                    if (empty($codNcm)) {
                        continue;
                    }

                    $registros[] = [
                        'cod_ncm'         => $codNcm,
                        'tipo_codigo'     => $anexo['TipoCodigo']    ?? 'NCM',
                        'cod_class_trib'  => $codClassTrib,
                        'nome_reduzido'   => $nomeReduzido,
                        'cst'             => $cstCod,
                        'nro_anexo'       => $anexo['NroAnexo']      ?? null,
                        'desc_condicao'   => $anexo['DescCondicao']  ?? null,
                        'desc_excecao'    => $anexo['DescExcecao']   ?? null,
                        'desc_item_anexo' => $anexo['DescItemAnexo'] ?? null,
                        'desc_anexo'      => $anexo['DescAnexo']     ?? null,
                        'tipo_permissao'  => $anexo['TipoPermissao'] ?? null,
                        'dth_ini_vig'     => $this->parseDate($anexo['DthIniVig'] ?? null),
                        'dth_fim_vig'     => $this->parseDate($anexo['DthFimVig'] ?? null),
                        'created_at'      => $agora,
                        'updated_at'      => $agora,
                    ];
                }
            }
        }

        return $registros;
    }

    private function parseDate(?string $data): ?string
    {
        if (empty($data)) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($data)->format('Y-m-d H:i:s');
        } catch (\Exception) {
            return null;
        }
    }
}
