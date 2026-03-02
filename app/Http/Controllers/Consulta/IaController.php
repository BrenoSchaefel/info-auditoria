<?php

namespace App\Http\Controllers\Consulta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class IaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('consulta.ia');
    }

    public function consultar(Request $request)
    {
        $request->validate([
            'descricao_produto' => ['required', 'string', 'max:2000'],
            'ncm'               => ['required', 'string', 'max:20'],
            'cfop'              => ['required', 'string', 'max:5'],
        ]);

        $ncm = preg_replace('/[^0-9]/', '', trim($request->ncm));

        if (empty($ncm)) {
            return response()->json([
                'encontrado' => false,
                'mensagem'   => 'Informe um código NCM válido.',
            ], 422);
        }

        // ── Etapa 1: Busca local (NCM × Classificação Tributária) ────────
        $classificacoesNcm = $this->buscarClassificacoesLocais($ncm);

        // 1 resultado na tabela NCM → retorna direto sem IA
        if ($classificacoesNcm->count() === 1) {
            return response()->json([
                'encontrado'    => true,
                'via'           => 'base_local',
                'mensagem'      => 'Classificação única encontrada na base local — não foi necessário consultar a IA.',
                'resultado'     => $classificacoesNcm->first(),
                'justificativa' => null,
            ]);
        }

        // 0 ou 2+ resultados → precisa da IA
        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            return response()->json([
                'encontrado' => false,
                'mensagem'   => 'A chave da API OpenAI não está configurada. Adicione <code>OPENAI_API_KEY</code> no arquivo <code>.env</code>.',
            ], 500);
        }

        // Se NCM encontrado com 2+ classificações → IA escolhe entre elas
        if ($classificacoesNcm->isNotEmpty()) {
            return $this->consultarGpt($request->all(), $classificacoesNcm, $ncm, 'ncm_multiplo');
        }

        // Se NCM não encontrado → busca todas as classificações (NF-e/NFC-e)
        // para reduzir o volume e envia para a IA determinar a mais adequada
        $todasClassificacoes = $this->buscarTodasClassificacoes();

        if ($todasClassificacoes->isEmpty()) {
            return response()->json([
                'encontrado' => false,
                'mensagem'   => 'Nenhuma classificação tributária cadastrada na base. Importe os dados antes de consultar.',
            ]);
        }

        return $this->consultarGpt($request->all(), $todasClassificacoes, $ncm, 'ncm_ausente');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Busca local — DISTINCT ON para uma linha por (ncm, cod_class_trib)
    // ─────────────────────────────────────────────────────────────────────
    private function buscarClassificacoesLocais(string $ncm)
    {
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

        return collect(DB::select($sql, [$ncm]));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Busca TODAS as classificações (NF-e/NFC-e) — para quando o NCM
    // não está na tabela ncm_classificacao_tributaria
    // ─────────────────────────────────────────────────────────────────────
    private function buscarTodasClassificacoes()
    {
        $sql = <<<SQL
            SELECT
                codigo    AS cod_class_trib,
                cst,
                descricao_cst,
                nome,
                nome      AS nome_reduzido,
                descricao,
                tipo_aliquota,
                aliq_red_ibs,
                aliq_red_cbs,
                ind_nfe,
                ind_nfce,
                ind_cte,
                data_inicio_vigencia AS ct_data_inicio,
                data_fim_vigencia    AS ct_data_fim,
                link
            FROM classificacao_tributaria
            WHERE (ind_nfe = 1 OR ind_nfce = 1)
            ORDER BY codigo
        SQL;

        return collect(DB::select($sql));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Chamada à API do GPT
    // ─────────────────────────────────────────────────────────────────────
    private function consultarGpt(array $payload, $classificacoes, string $ncm, string $tipo)
    {
        $systemPrompt = $this->montarSystemPrompt($tipo);
        $userPrompt   = $this->montarUserPrompt($payload, $classificacoes, $tipo);

        $model  = config('services.openai.model', 'gpt-4o');
        $apiKey = config('services.openai.key');

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'           => $model,
                    'response_format' => ['type' => 'json_object'],
                    'temperature'     => 0.1,
                    'max_tokens'      => 1000,
                    'messages'        => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => $userPrompt],
                    ],
                ]);

            if ($response->failed()) {
                $erro = $response->json('error.message', 'Erro desconhecido na API OpenAI.');

                return response()->json([
                    'encontrado' => false,
                    'mensagem'   => "Erro ao consultar a IA: {$erro}",
                ], 502);
            }

            $conteudo = $response->json('choices.0.message.content', '');
            $resultado = json_decode($conteudo, true);

            if (! $resultado || empty($resultado['cod_class_trib'])) {
                return response()->json([
                    'encontrado' => false,
                    'mensagem'   => 'A IA retornou uma resposta inválida. Tente novamente.',
                ], 502);
            }

            $codigoEscolhido = trim($resultado['cod_class_trib']);
            $justificativa   = $resultado['justificativa'] ?? '';

            // Validação: o código DEVE estar na lista de candidatos
            $codigosValidos = $classificacoes->pluck('cod_class_trib')->toArray();

            if (! in_array($codigoEscolhido, $codigosValidos)) {
                return response()->json([
                    'encontrado' => false,
                    'mensagem'   => "A IA retornou o código <strong>{$codigoEscolhido}</strong>, que não está entre as classificações válidas. Tente novamente ou revise os dados.",
                ], 422);
            }

            // Busca os dados completos da classificação escolhida
            $escolhido = $classificacoes->firstWhere('cod_class_trib', $codigoEscolhido);

            $mensagem = $tipo === 'ncm_ausente'
                ? 'O NCM não foi encontrado na base local. A IA analisou todas as classificações disponíveis e sugeriu a mais adequada.'
                : 'A IA analisou as classificações candidatas para este NCM e sugeriu a mais adequada.';

            return response()->json([
                'encontrado'       => true,
                'via'              => 'ia',
                'tipo'             => $tipo,
                'mensagem'         => $mensagem,
                'resultado'        => $escolhido,
                'justificativa'    => $justificativa,
                'total_candidatos' => $classificacoes->count(),
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'encontrado' => false,
                'mensagem'   => 'Não foi possível conectar à API OpenAI. Verifique sua conexão com a internet.',
            ], 504);
        } catch (\Exception $e) {
            return response()->json([
                'encontrado' => false,
                'mensagem'   => 'Erro inesperado ao consultar a IA: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // System prompt — restritivo para evitar alucinações
    // ─────────────────────────────────────────────────────────────────────
    private function montarSystemPrompt(string $tipo): string
    {
        $contextoExtra = $tipo === 'ncm_ausente'
            ? "\nATENÇÃO: O NCM informado NÃO foi encontrado na tabela de NCM × Classificação Tributária. Você receberá TODAS as classificações tributárias disponíveis (filtradas por aplicabilidade a NF-e/NFC-e). Analise com cuidado o NCM, a descrição do produto e o contexto da operação para escolher a classificação mais adequada."
            : "\nO NCM foi encontrado na base local com múltiplas classificações possíveis. Escolha a mais adequada com base no contexto do produto e da operação.";

        return <<<PROMPT
Você é um especialista em classificação tributária brasileira, com foco na Reforma Tributária (Lei Complementar 214/2025 — IBS e CBS).

Sua tarefa é: dado um produto com seus dados fiscais, e uma lista de classificações tributárias candidatas (todas válidas e existentes na base oficial), escolher A MAIS ADEQUADA.
{$contextoExtra}

REGRAS ABSOLUTAS — siga à risca:
1. Responda SOMENTE em JSON válido, no formato: {"cod_class_trib": "CODIGO", "justificativa": "texto explicativo"}
2. O campo "cod_class_trib" DEVE conter EXATAMENTE um dos códigos listados na seção CLASSIFICAÇÕES CANDIDATAS.
3. NUNCA invente, altere ou sugira um código que NÃO esteja na lista de candidatos.
4. Se não tiver certeza absoluta, escolha a classificação mais genérica ou abrangente entre as opções.
5. A justificativa deve ser clara, objetiva e citar os fatores decisivos (descrição do produto, NCM, tipo de operação, destinação, legislação).
6. Considere a vigência das classificações — prefira classificações em vigor.
7. Considere o CFOP e o tipo de operação para validar a consistência da classificação.
8. Se o produto possui CEST, isso indica regime de substituição tributária — considere isso na análise.
9. Analise as condições de aplicação de cada candidata para verificar qual se aplica ao contexto.
10. Não inclua nenhum texto fora do JSON. Sua resposta deve ser APENAS o JSON.
PROMPT;
    }

    // ─────────────────────────────────────────────────────────────────────
    // User prompt — contexto detalhado do produto + lista de candidatos
    // ─────────────────────────────────────────────────────────────────────
    private function montarUserPrompt(array $payload, $classificacoes, string $tipo): string
    {
        // Dados do produto
        $regime = ($payload['regime'] ?? 'normal') === 'simples' ? 'Simples Nacional' : 'Lucro Real / Presumido';
        $codTrib = ($payload['regime'] ?? 'normal') === 'simples'
            ? 'CSOSN: ' . ($payload['csosn'] ?? '—')
            : 'CST: '   . ($payload['cst']   ?? '—');

        $tipoOp = str_replace('_', ' ', $payload['tipo_operacao'] ?? '—');
        $dest   = str_replace('_', ' ', $payload['destinacao'] ?? '—');

        $temSt = match($payload['tem_st'] ?? 'nao') {
            'retido'      => 'ICMS-ST retido anteriormente',
            'substituto'  => 'Contribuinte substituto (responsável pelo recolhimento)',
            default       => 'Não possui ST',
        };

        $produto = <<<DADOS
=== DADOS DO PRODUTO ===
Descrição: {$payload['descricao_produto']}
NCM: {$payload['ncm']}
CEST: {$this->valorOu($payload, 'cest', 'Não informado')}
CFOP: {$payload['cfop']}
Regime tributário: {$regime}
{$codTrib}
Alíquota ICMS: {$this->valorOu($payload, 'aliq_icms', 'Não informada')}
Alíquota IPI: {$this->valorOu($payload, 'aliq_ipi', 'Não informada')}
Alíquota PIS: {$this->valorOu($payload, 'aliq_pis', 'Não informada')}
Alíquota COFINS: {$this->valorOu($payload, 'aliq_cofins', 'Não informada')}
Substituição tributária: {$temSt}
Tipo de operação: {$tipoOp}
Destinação: {$dest}
UF de origem: {$this->valorOu($payload, 'uf_origem', 'Não informada')}
UF de destino: {$this->valorOu($payload, 'uf_destino', 'Não informada')}
Observações: {$this->valorOu($payload, 'observacoes', 'Nenhuma')}
DADOS;

        // Lista de classificações candidatas
        $lista = "=== CLASSIFICAÇÕES CANDIDATAS ===\n";
        $lista .= "Total: {$classificacoes->count()} opções\n\n";

        foreach ($classificacoes as $i => $c) {
            $idx = $i + 1;

            // Campos que existem nos dois formatos
            $codigo     = $c->cod_class_trib;
            $nome       = $c->nome_reduzido ?? $c->nome ?? '—';
            $cst        = $c->cst ?? '—';
            $descCst    = $c->descricao_cst ?? '—';
            $descricao  = $c->descricao ?? '—';
            $tipoAliq   = $c->tipo_aliquota ?? '—';
            $redIbs     = $c->aliq_red_ibs ?? '0';
            $redCbs     = $c->aliq_red_cbs ?? '0';
            $nfe        = $this->simNao($c->ind_nfe ?? null);
            $nfce       = $this->simNao($c->ind_nfce ?? null);

            // Vigência
            $vigIni = $c->dth_ini_vig ?? $c->ct_data_inicio ?? null;
            $vigFim = $c->dth_fim_vig ?? $c->ct_data_fim ?? null;
            $vigencia = $this->formatarVigencia($vigIni, $vigFim);

            $lista .= "--- Opção {$idx} ---\n";
            $lista .= "Código (cod_class_trib): {$codigo}\n";
            $lista .= "Nome: {$nome}\n";
            $lista .= "CST: {$cst}\n";
            $lista .= "Descrição CST: {$descCst}\n";
            $lista .= "Descrição completa: {$descricao}\n";
            $lista .= "Tipo de alíquota: {$tipoAliq}\n";
            $lista .= "Redução IBS: {$redIbs}%\n";
            $lista .= "Redução CBS: {$redCbs}%\n";

            // Campos que só existem quando veio do join NCM
            if ($tipo === 'ncm_multiplo') {
                $lista .= "Nº do anexo: " . ($c->nro_anexo ?? '—') . "\n";
                $lista .= "Descrição do item no anexo: " . ($c->desc_item_anexo ?? '—') . "\n";
                $lista .= "Descrição do anexo: " . ($c->desc_anexo ?? '—') . "\n";
                $lista .= "Condição de aplicação: " . ($c->desc_condicao ?? '—') . "\n";
                $lista .= "Exceção: " . ($c->desc_excecao ?? '—') . "\n";
            }

            $lista .= "Vigência: {$vigencia}\n";
            $lista .= "Aplicável NF-e: {$nfe}\n";
            $lista .= "Aplicável NFC-e: {$nfce}\n\n";
        }

        $instrucao = <<<INSTR
=== INSTRUÇÃO ===
Com base nos dados do produto acima e nas classificações candidatas listadas, escolha EXATAMENTE UMA classificação tributária (cod_class_trib) que seja a mais adequada para este produto/operação.
Responda no formato JSON: {"cod_class_trib": "CODIGO_ESCOLHIDO", "justificativa": "Explicação detalhada do motivo da escolha"}
INSTR;

        return $produto . "\n\n" . $lista . "\n" . $instrucao;
    }

    private function valorOu(array $data, string $key, string $fallback): string
    {
        $v = trim($data[$key] ?? '');

        return $v !== '' ? $v : $fallback;
    }

    private function simNao($val): string
    {
        return $val == 1 ? 'Sim' : 'Não';
    }

    private function formatarVigencia($inicio, $fim): string
    {
        $parts = [];

        if ($inicio) {
            $parts[] = 'Início: ' . date('d/m/Y', strtotime($inicio));
        }

        if ($fim) {
            $parts[] = 'Fim: ' . date('d/m/Y', strtotime($fim));
        }

        return $parts ? implode(' | ', $parts) : 'Sem vigência definida';
    }
}
