<?php

namespace App\Http\Controllers\Importacao;

use App\Http\Controllers\Controller;
use App\Models\ClassificacaoTributaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassificacaoTributariaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $total  = ClassificacaoTributaria::count();
        $ultima = ClassificacaoTributaria::latest('updated_at')->value('updated_at');

        return view('importacao.classificacao-tributaria.index', compact('total', 'ultima'));
    }

    public function importar(Request $request)
    {
        $request->validate([
            'arquivo' => ['required', 'file', 'mimes:csv,txt', 'max:20480'],
        ], [
            'arquivo.required' => 'Selecione um arquivo CSV.',
            'arquivo.mimes'    => 'O arquivo deve ser do tipo CSV.',
            'arquivo.max'      => 'O arquivo não pode ultrapassar 20 MB.',
        ]);

        $caminho  = $request->file('arquivo')->getRealPath();
        $handle   = fopen($caminho, 'r');

        if ($handle === false) {
            return back()->with('erro', 'Não foi possível ler o arquivo enviado.');
        }

        // Detecta o delimitador (vírgula ou ponto-e-vírgula)
        $primeiraLinha = fgets($handle);
        rewind($handle);
        $delimitador = substr_count($primeiraLinha, ';') >= substr_count($primeiraLinha, ',') ? ';' : ',';

        // Lê e descarta o cabeçalho
        fgetcsv($handle, 0, $delimitador);

        $colunas   = ClassificacaoTributaria::$csvColunas;
        $registros = [];
        $agora     = now();
        $linha     = 1;

        while (($linha_csv = fgetcsv($handle, 0, $delimitador)) !== false) {
            $linha++;

            // Ignora linhas completamente vazias
            if (count(array_filter($linha_csv, fn($v) => trim($v) !== '')) === 0) {
                continue;
            }

            $registro = [];
            foreach ($colunas as $i => $coluna) {
                $valor = isset($linha_csv[$i]) ? trim($linha_csv[$i]) : null;
                $registro[$coluna] = $this->converterValor($coluna, $valor);
            }

            $registro['created_at'] = $agora;
            $registro['updated_at'] = $agora;
            $registros[] = $registro;
        }

        fclose($handle);

        if (empty($registros)) {
            return back()->with('erro', 'Nenhum dado encontrado no arquivo CSV.');
        }

        DB::transaction(function () use ($registros) {
            ClassificacaoTributaria::truncate();

            foreach (array_chunk($registros, 500) as $lote) {
                ClassificacaoTributaria::insert($lote);
            }
        });

        return back()->with('sucesso', 'Importação concluída com sucesso! ' . count($registros) . ' registros importados.');
    }

    private function converterValor(string $coluna, ?string $valor): mixed
    {
        // Campos de data
        if (in_array($coluna, ['data_inicio_vigencia', 'data_fim_vigencia', 'data_atualizacao'])) {
            if (empty($valor)) return null;
            try {
                return \Carbon\Carbon::parse($valor)->format('Y-m-d');
            } catch (\Exception) {
                return null;
            }
        }

        // Campos numéricos decimais
        if (in_array($coluna, ['aliq_red_ibs', 'aliq_red_cbs'])) {
            if ($valor === null || $valor === '') return 0;
            // Aceita vírgula como separador decimal
            return (float) str_replace(',', '.', $valor);
        }

        // Campos inteiros (indicadores)
        if (str_starts_with($coluna, 'ind_')) {
            if ($valor === null || $valor === '') return null;
            return (int) $valor;
        }

        // String vazia → null
        return ($valor === '' ? null : $valor);
    }
}
