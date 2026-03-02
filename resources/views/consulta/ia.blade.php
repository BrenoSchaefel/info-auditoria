@extends('layouts.app')

@section('title', 'Consulta com IA')

@section('content')

<div class="page-header">
    <div class="page-header__inner">
        <div>
            <h1 class="page-title">Consulta com IA</h1>
            <p class="page-subtitle">Preencha os dados do produto para obter uma análise tributária assistida por inteligência artificial.</p>
        </div>
        <div class="ia-badge">
            <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M9 9a1 1 0 0 0-1 1v1a1 1 0 0 0 2 0v-1a1 1 0 0 0-1-1m6 0a1 1 0 0 0-1 1v1a1 1 0 0 0 2 0v-1a1 1 0 0 0-1-1z"/></svg>
            IA Tributária
        </div>
    </div>
</div>

<div class="ia-layout">

    {{-- ── Formulário ─────────────────────────────────── --}}
    <div class="ia-form-col">

        <form id="iaForm" novalidate>
            @csrf

            {{-- Bloco 1: Identificação do produto --}}
            <div class="form-section">
                <div class="form-section__header">
                    <div class="form-section__icon form-section__icon--blue">
                        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    </div>
                    <div>
                        <h2 class="form-section__title">Identificação do Produto</h2>
                        <p class="form-section__desc">Dados básicos que identificam o produto fiscalmente.</p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="field field--full">
                        <label class="field__label" for="descricao_produto">
                            Descrição do produto
                            <span class="field__required">*</span>
                        </label>
                        <textarea
                            id="descricao_produto"
                            name="descricao_produto"
                            class="field__textarea"
                            rows="3"
                            placeholder="Descreva o produto com o máximo de detalhes possível (composição, finalidade, características técnicas...)"
                            required
                        ></textarea>
                        <p class="field__hint">Quanto mais detalhada a descrição, mais precisa será a análise.</p>
                    </div>

                    <div class="field">
                        <label class="field__label" for="ncm">
                            NCM
                            <span class="field__required">*</span>
                        </label>
                        <div class="field__input-wrap">
                            <span class="field__prefix">
                                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                            </span>
                            <input
                                type="text"
                                id="ncm"
                                name="ncm"
                                class="field__input field__input--prefix field__input--mono"
                                placeholder="00000000"
                                maxlength="10"
                                autocomplete="off"
                                inputmode="numeric"
                                required
                            >
                        </div>
                        <p class="field__hint">Código NCM de 8 dígitos (apenas números).</p>
                    </div>

                    <div class="field">
                        <label class="field__label" for="cest">
                            CEST
                            <span class="field__optional">(se aplicável)</span>
                        </label>
                        <div class="field__input-wrap">
                            <span class="field__prefix">
                                <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            </span>
                            <input
                                type="text"
                                id="cest"
                                name="cest"
                                class="field__input field__input--prefix field__input--mono"
                                placeholder="0000000"
                                maxlength="9"
                                autocomplete="off"
                                inputmode="numeric"
                            >
                        </div>
                        <p class="field__hint">Código Especificador da Substituição Tributária (7 dígitos).</p>
                    </div>

                </div>
            </div>

            {{-- Bloco 2: Classificação fiscal --}}
            <div class="form-section">
                <div class="form-section__header">
                    <div class="form-section__icon form-section__icon--violet">
                        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <div>
                        <h2 class="form-section__title">Classificação Fiscal</h2>
                        <p class="form-section__desc">Códigos utilizados na emissão dos documentos fiscais.</p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="field">
                        <label class="field__label" for="cfop">
                            CFOP
                            <span class="field__required">*</span>
                        </label>
                        <div class="field__input-wrap">
                            <span class="field__prefix">
                                <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                            </span>
                            <input
                                type="text"
                                id="cfop"
                                name="cfop"
                                class="field__input field__input--prefix field__input--mono"
                                placeholder="0000"
                                maxlength="5"
                                autocomplete="off"
                                inputmode="numeric"
                                required
                            >
                        </div>
                        <p class="field__hint">Código Fiscal de Operações e Prestações (4 dígitos).</p>
                    </div>

                    <div class="field">
                        <label class="field__label">Regime tributário do emissor</label>
                        <div class="toggle-group" id="regimeToggle">
                            <button type="button" class="toggle-btn active" data-value="normal">Lucro Real / Presumido</button>
                            <button type="button" class="toggle-btn" data-value="simples">Simples Nacional</button>
                        </div>
                        <input type="hidden" name="regime" id="regimeValue" value="normal">
                    </div>

                    <div class="field" id="cstField">
                        <label class="field__label" for="cst">
                            CST — Código de Situação Tributária
                            <span class="field__required">*</span>
                        </label>
                        <div class="field__input-wrap">
                            <span class="field__prefix">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            </span>
                            <input
                                type="text"
                                id="cst"
                                name="cst"
                                class="field__input field__input--prefix field__input--mono"
                                placeholder="000"
                                maxlength="3"
                                autocomplete="off"
                                inputmode="numeric"
                            >
                        </div>
                        <p class="field__hint">3 dígitos: origem (1) + tributação (2).</p>
                    </div>

                    <div class="field" id="csosnField" style="display:none">
                        <label class="field__label" for="csosn">
                            CSOSN — Código de Situação da Operação no Simples Nacional
                            <span class="field__required">*</span>
                        </label>
                        <div class="field__input-wrap">
                            <span class="field__prefix">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            </span>
                            <input
                                type="text"
                                id="csosn"
                                name="csosn"
                                class="field__input field__input--prefix field__input--mono"
                                placeholder="000"
                                maxlength="3"
                                autocomplete="off"
                                inputmode="numeric"
                            >
                        </div>
                        <p class="field__hint">3 dígitos — específico para Simples Nacional.</p>
                    </div>

                </div>
            </div>

            {{-- Bloco 3: Tributação --}}
            <div class="form-section">
                <div class="form-section__header">
                    <div class="form-section__icon form-section__icon--amber">
                        <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <h2 class="form-section__title">Tributação</h2>
                        <p class="form-section__desc">Alíquotas e bases de cálculo aplicadas na operação.</p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="field">
                        <label class="field__label" for="aliq_icms">Alíquota ICMS (%)</label>
                        <div class="field__input-wrap">
                            <span class="field__prefix">%</span>
                            <input
                                type="text"
                                id="aliq_icms"
                                name="aliq_icms"
                                class="field__input field__input--prefix"
                                placeholder="12,00"
                                maxlength="7"
                                autocomplete="off"
                                inputmode="decimal"
                            >
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label" for="aliq_ipi">Alíquota IPI (%)</label>
                        <div class="field__input-wrap">
                            <span class="field__prefix">%</span>
                            <input
                                type="text"
                                id="aliq_ipi"
                                name="aliq_ipi"
                                class="field__input field__input--prefix"
                                placeholder="0,00"
                                maxlength="7"
                                autocomplete="off"
                                inputmode="decimal"
                            >
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label" for="aliq_pis">Alíquota PIS (%)</label>
                        <div class="field__input-wrap">
                            <span class="field__prefix">%</span>
                            <input
                                type="text"
                                id="aliq_pis"
                                name="aliq_pis"
                                class="field__input field__input--prefix"
                                placeholder="0,65"
                                maxlength="7"
                                autocomplete="off"
                                inputmode="decimal"
                            >
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label" for="aliq_cofins">Alíquota COFINS (%)</label>
                        <div class="field__input-wrap">
                            <span class="field__prefix">%</span>
                            <input
                                type="text"
                                id="aliq_cofins"
                                name="aliq_cofins"
                                class="field__input field__input--prefix"
                                placeholder="3,00"
                                maxlength="7"
                                autocomplete="off"
                                inputmode="decimal"
                            >
                        </div>
                    </div>

                    <div class="field field--full">
                        <label class="field__label">Substituição Tributária (ST)</label>
                        <div class="radio-group">
                            <label class="radio-opt">
                                <input type="radio" name="tem_st" value="nao" checked>
                                <span class="radio-opt__box"></span>
                                <span class="radio-opt__label">Não possui ST</span>
                            </label>
                            <label class="radio-opt">
                                <input type="radio" name="tem_st" value="retido">
                                <span class="radio-opt__box"></span>
                                <span class="radio-opt__label">ICMS-ST retido anteriormente</span>
                            </label>
                            <label class="radio-opt">
                                <input type="radio" name="tem_st" value="substituto">
                                <span class="radio-opt__box"></span>
                                <span class="radio-opt__label">Contribuinte substituto (responsável pelo recolhimento)</span>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Bloco 4: Contexto da operação --}}
            <div class="form-section">
                <div class="form-section__header">
                    <div class="form-section__icon form-section__icon--green">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    </div>
                    <div>
                        <h2 class="form-section__title">Contexto da Operação</h2>
                        <p class="form-section__desc">Informações sobre a natureza e destino da operação.</p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="field">
                        <label class="field__label">Tipo de operação</label>
                        <div class="select-wrap">
                            <select id="tipo_operacao" name="tipo_operacao" class="field__select">
                                <option value="">Selecione...</option>
                                <option value="saida_interna">Saída interna (mesmo estado)</option>
                                <option value="saida_interestadual">Saída interestadual</option>
                                <option value="entrada">Entrada / Aquisição</option>
                                <option value="importacao">Importação</option>
                                <option value="exportacao">Exportação</option>
                                <option value="devolucao">Devolução</option>
                            </select>
                            <svg class="select-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label">Destinação do produto</label>
                        <div class="select-wrap">
                            <select id="destinacao" name="destinacao" class="field__select">
                                <option value="">Selecione...</option>
                                <option value="comercializacao">Comercialização / Revenda</option>
                                <option value="industrializacao">Industrialização / Insumo</option>
                                <option value="uso_consumo">Uso e consumo</option>
                                <option value="ativo">Ativo imobilizado</option>
                                <option value="consumidor_final">Consumidor final</option>
                            </select>
                            <svg class="select-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label" for="uf_origem">UF de origem</label>
                        <div class="select-wrap">
                            <select id="uf_origem" name="uf_origem" class="field__select">
                                <option value="">Selecione...</option>
                                @foreach(['AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MG','MS','MT','PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO'] as $uf)
                                    <option value="{{ $uf }}">{{ $uf }}</option>
                                @endforeach
                            </select>
                            <svg class="select-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label" for="uf_destino">UF de destino</label>
                        <div class="select-wrap">
                            <select id="uf_destino" name="uf_destino" class="field__select">
                                <option value="">Selecione...</option>
                                @foreach(['AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MG','MS','MT','PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO'] as $uf)
                                    <option value="{{ $uf }}">{{ $uf }}</option>
                                @endforeach
                            </select>
                            <svg class="select-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>

                    <div class="field field--full">
                        <label class="field__label" for="observacoes">Observações adicionais</label>
                        <textarea
                            id="observacoes"
                            name="observacoes"
                            class="field__textarea"
                            rows="3"
                            placeholder="Informações complementares relevantes para a análise: benefícios fiscais, regimes especiais, particularidades do produto, dúvida específica..."
                        ></textarea>
                    </div>

                </div>
            </div>

            {{-- Botão de envio --}}
            <div class="form-actions">
                <button type="button" class="btn-secondary" id="clearFormBtn">
                    <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                    Limpar formulário
                </button>
                <button type="submit" class="btn-primary" id="submitBtn">
                    <span class="btn-label">
                        <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2z"/></svg>
                        Consultar com IA
                    </span>
                    <span class="btn-loading" style="display:none">
                        <span class="spinner"></span>
                        Analisando...
                    </span>
                </button>
            </div>

        </form>

    </div>

    {{-- ── Painel de resultado ─────────────────────────── --}}
    <div class="ia-result-col">

        {{-- Estado: inicial (aguardando) --}}
        <div class="ia-result-placeholder" id="resultPlaceholder">
            <div class="placeholder-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M9 9a1 1 0 0 0-1 1v1a1 1 0 0 0 2 0v-1a1 1 0 0 0-1-1m6 0a1 1 0 0 0-1 1v1a1 1 0 0 0 2 0v-1a1 1 0 0 0-1-1z"/></svg>
            </div>
            <p class="placeholder-title">Análise tributária com IA</p>
            <p class="placeholder-desc">Preencha os campos ao lado e clique em <strong>Consultar com IA</strong> para receber uma análise detalhada da situação tributária do produto.</p>
            <div class="placeholder-features">
                <div class="placeholder-feature">
                    <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Verificação da classificação NCM
                </div>
                <div class="placeholder-feature">
                    <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Análise de conformidade dos códigos fiscais
                </div>
                <div class="placeholder-feature">
                    <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Impacto na Reforma Tributária (IBS/CBS)
                </div>
                <div class="placeholder-feature">
                    <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Alertas e recomendações
                </div>
            </div>
        </div>

        {{-- Estado: loading --}}
        <div class="ia-result-loading" id="resultLoading" style="display:none">
            <div class="loading-pulse">
                <div class="loading-pulse__ring"></div>
                <div class="loading-pulse__ring loading-pulse__ring--2"></div>
                <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2z"/></svg>
            </div>
            <p class="loading-title">Analisando...</p>
            <p class="loading-desc">A IA está processando os dados tributários do produto. Aguarde um momento.</p>
        </div>

        {{-- Estado: resultado --}}
        <div class="ia-result-content" id="resultContent" style="display:none">
            <div class="result-panel-header">
                <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Análise concluída
                <span class="result-panel-ncm" id="resultNcm"></span>
            </div>
            <div class="result-panel-body" id="resultBody"></div>
        </div>

        {{-- Estado: erro --}}
        <div class="ia-result-error" id="resultError" style="display:none">
            <div class="error-icon">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <p class="error-title">Erro na consulta</p>
            <p class="error-msg" id="errorMsg"></p>
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    /* ─── Layout geral ────────────────────────────────── */
    .page-header__inner {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .ia-badge {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .4rem 1rem;
        background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
        color: #fff;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 600;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(124,58,237,.3);
        flex-shrink: 0;
    }

    .ia-badge svg {
        width: 15px; height: 15px;
        fill: #fff;
        flex-shrink: 0;
    }

    .ia-layout {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 1.5rem;
        align-items: start;
    }

    /* ─── Seções do formulário ────────────────────────── */
    .form-section {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .form-section__header {
        display: flex;
        align-items: center;
        gap: .9rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg);
    }

    .form-section__icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .form-section__icon svg {
        width: 17px; height: 17px;
        fill: none;
        stroke: #fff;
        stroke-width: 1.8;
        stroke-linecap: round; stroke-linejoin: round;
    }

    .form-section__icon--blue   { background: linear-gradient(135deg, #2563eb, #6366f1); }
    .form-section__icon--violet { background: linear-gradient(135deg, #7c3aed, #a855f7); }
    .form-section__icon--amber  { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .form-section__icon--green  { background: linear-gradient(135deg, #16a34a, #22c55e); }

    .form-section__title {
        font-size: .9rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: .1rem;
    }

    .form-section__desc {
        font-size: .75rem;
        color: var(--text-muted);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem 1.25rem;
        padding: 1.25rem;
    }

    /* ─── Campos ──────────────────────────────────────── */
    .field {
        display: flex;
        flex-direction: column;
        gap: .3rem;
    }

    .field--full { grid-column: 1 / -1; }

    .field__label {
        font-size: .75rem;
        font-weight: 600;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: .35rem;
    }

    .field__required { color: #ef4444; }
    .field__optional { font-weight: 400; color: var(--text-muted); font-size: .7rem; }

    .field__input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .field__prefix {
        position: absolute;
        left: .75rem;
        color: var(--text-muted);
        pointer-events: none;
        display: flex;
        align-items: center;
        font-size: .82rem;
        font-weight: 500;
    }

    .field__prefix svg {
        width: 15px; height: 15px;
        stroke: currentColor; fill: none;
        stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }

    .field__input,
    .field__textarea,
    .field__select {
        width: 100%;
        padding: .6rem .75rem;
        font-size: .85rem;
        font-family: inherit;
        color: var(--text);
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        outline: none;
        transition: border-color .18s, box-shadow .18s, background .18s;
        appearance: none;
    }

    .field__input:focus,
    .field__textarea:focus,
    .field__select:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
    }

    .field__input--prefix { padding-left: 2.35rem; }
    .field__input--mono { font-family: 'Courier New', monospace; font-weight: 600; letter-spacing: .06em; }

    .field__textarea { resize: vertical; min-height: 80px; }

    .field__hint {
        font-size: .7rem;
        color: var(--text-light);
        line-height: 1.4;
    }

    /* ─── Select ──────────────────────────────────────── */
    .select-wrap {
        position: relative;
    }

    .select-wrap .field__select { padding-right: 2rem; cursor: pointer; }

    .select-arrow {
        position: absolute;
        right: .65rem;
        top: 50%; transform: translateY(-50%);
        width: 15px; height: 15px;
        stroke: var(--text-muted); fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        pointer-events: none;
    }

    /* ─── Toggle grupo (regime) ───────────────────────── */
    .toggle-group {
        display: flex;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        overflow: hidden;
        background: var(--bg);
    }

    .toggle-btn {
        flex: 1;
        padding: .6rem .5rem;
        font-size: .78rem;
        font-weight: 500;
        font-family: inherit;
        color: var(--text-muted);
        background: none;
        border: none;
        cursor: pointer;
        transition: background .18s, color .18s;
        text-align: center;
        line-height: 1.3;
    }

    .toggle-btn + .toggle-btn {
        border-left: 1.5px solid var(--border);
    }

    .toggle-btn.active {
        background: var(--primary);
        color: #fff;
        font-weight: 600;
    }

    /* ─── Radio group (ST) ────────────────────────────── */
    .radio-group {
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .radio-opt {
        display: flex;
        align-items: center;
        gap: .6rem;
        cursor: pointer;
        padding: .5rem .75rem;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        background: var(--bg);
        transition: border-color .18s, background .18s;
    }

    .radio-opt:hover { border-color: var(--primary); background: var(--primary-light); }

    .radio-opt input[type="radio"] { display: none; }

    .radio-opt__box {
        width: 16px; height: 16px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: #fff;
        flex-shrink: 0;
        position: relative;
        transition: border-color .18s;
    }

    .radio-opt input[type="radio"]:checked ~ .radio-opt__box {
        border-color: var(--primary);
    }

    .radio-opt input[type="radio"]:checked ~ .radio-opt__box::after {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 7px; height: 7px;
        background: var(--primary);
        border-radius: 50%;
    }

    .radio-opt input[type="radio"]:checked ~ .radio-opt__label {
        color: var(--text);
        font-weight: 600;
    }

    .radio-opt:has(input[type="radio"]:checked) {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .radio-opt__label {
        font-size: .82rem;
        color: var(--text-muted);
        transition: color .18s;
    }

    /* ─── Botões de ação ──────────────────────────────── */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .75rem;
        padding-top: .25rem;
    }

    .btn-primary,
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .7rem 1.5rem;
        font-size: .88rem;
        font-weight: 600;
        font-family: inherit;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: box-shadow .18s, transform .18s, opacity .18s;
        white-space: nowrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
        color: #fff;
        box-shadow: 0 2px 10px rgba(124,58,237,.3);
        min-width: 180px;
        justify-content: center;
    }

    .btn-primary:hover { box-shadow: 0 4px 14px rgba(124,58,237,.4); }
    .btn-primary:active { transform: scale(.97); }
    .btn-primary:disabled { opacity: .55; cursor: not-allowed; transform: none; }

    .btn-label, .btn-loading {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
    }

    .btn-label svg, .btn-loading svg {
        width: 16px; height: 16px;
        fill: currentColor;
    }

    .btn-secondary {
        background: var(--surface);
        color: var(--text-muted);
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .btn-secondary:hover { color: var(--text); background: var(--bg); }
    .btn-secondary:active { transform: scale(.97); }

    .btn-secondary svg {
        width: 14px; height: 14px;
        stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }

    .spinner {
        width: 15px; height: 15px;
        border: 2px solid rgba(255,255,255,.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .6s linear infinite;
        display: block;
        flex-shrink: 0;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* ─── Coluna de resultado ─────────────────────────── */
    .ia-result-col {
        position: sticky;
        top: calc(var(--topbar-h) + 1.75rem);
    }

    /* Placeholder */
    .ia-result-placeholder {
        background: var(--surface);
        border: 1.5px dashed var(--border);
        border-radius: var(--radius);
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .placeholder-icon {
        width: 56px; height: 56px;
        background: linear-gradient(135deg, #f5f3ff, #eff6ff);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
    }

    .placeholder-icon svg {
        width: 28px; height: 28px;
        fill: #7c3aed;
    }

    .placeholder-title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: .4rem;
    }

    .placeholder-desc {
        font-size: .8rem;
        color: var(--text-muted);
        line-height: 1.55;
        margin-bottom: 1.25rem;
    }

    .placeholder-features {
        display: flex;
        flex-direction: column;
        gap: .5rem;
        text-align: left;
    }

    .placeholder-feature {
        display: flex;
        align-items: center;
        gap: .55rem;
        font-size: .78rem;
        color: var(--text-muted);
    }

    .placeholder-feature svg {
        width: 14px; height: 14px;
        stroke: #16a34a; fill: none;
        stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round;
        flex-shrink: 0;
    }

    /* Loading */
    .ia-result-loading {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 2.5rem 1.5rem;
        text-align: center;
    }

    .loading-pulse {
        width: 70px; height: 70px;
        position: relative;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.25rem;
    }

    .loading-pulse__ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid rgba(124,58,237,.2);
        animation: pulse-ring 1.5s ease-out infinite;
    }

    .loading-pulse__ring--2 { animation-delay: .5s; }

    @keyframes pulse-ring {
        0%   { transform: scale(.7); opacity: .8; }
        100% { transform: scale(1.3); opacity: 0; }
    }

    .loading-pulse svg {
        width: 32px; height: 32px;
        fill: #7c3aed;
        animation: float 2s ease-in-out infinite;
        position: relative;
        z-index: 1;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-4px); }
    }

    .loading-title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: .3rem;
    }

    .loading-desc {
        font-size: .78rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* Resultado */
    .ia-result-content {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        animation: cardIn .3s cubic-bezier(.16,1,.3,1);
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .result-panel-header {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .9rem 1.25rem;
        background: linear-gradient(135deg, #f5f3ff, #eff6ff);
        border-bottom: 1px solid #e9d5ff;
        font-size: .85rem;
        font-weight: 700;
        color: #7c3aed;
    }

    .result-panel-header svg {
        width: 16px; height: 16px;
        stroke: #16a34a; fill: none;
        stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round;
    }

    .result-panel-ncm {
        margin-left: auto;
        font-family: 'Courier New', monospace;
        font-size: .8rem;
        font-weight: 700;
        background: #fff;
        color: var(--primary);
        border: 1px solid #bfdbfe;
        padding: .15rem .55rem;
        border-radius: 999px;
    }

    .result-panel-body {
        padding: 1.25rem;
        font-size: .82rem;
        color: var(--text);
        line-height: 1.6;
        max-height: 70vh;
        overflow-y: auto;
    }

    /* Erro */
    .ia-result-error {
        background: var(--surface);
        border: 1px solid #fecaca;
        border-radius: var(--radius);
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .error-icon {
        width: 52px; height: 52px;
        background: #fef2f2;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto .9rem;
    }

    .error-icon svg {
        width: 24px; height: 24px;
        stroke: #ef4444; fill: none;
        stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }

    .error-title {
        font-size: .9rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: .3rem;
    }

    .error-msg {
        font-size: .8rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* ─── Via badge (base local / IA) ────────────────── */
    .via-badge {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .4rem .85rem;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 600;
        margin-bottom: .5rem;
    }

    .via-badge svg {
        width: 14px; height: 14px;
        flex-shrink: 0;
    }

    .via-badge--local {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .via-badge--local svg {
        stroke: #16a34a; fill: none;
        stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round;
    }

    .via-badge--ia {
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
    }

    .via-badge--ia svg { fill: #7c3aed; width: 13px; height: 13px; }

    .via-candidates {
        font-weight: 400;
        opacity: .75;
    }

    .via-msg {
        font-size: .78rem;
        color: var(--text-muted);
        margin-bottom: .85rem;
        line-height: 1.5;
    }

    /* ─── Card de resultado ───────────────────────────── */
    .res-card {
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        overflow: hidden;
        margin-bottom: .85rem;
    }

    .res-card__header {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .75rem 1rem;
        background: var(--bg);
        border-bottom: 1px solid var(--border);
    }

    .res-card__cst {
        font-family: 'Courier New', monospace;
        font-size: .75rem;
        font-weight: 700;
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
        padding: .15rem .5rem;
        border-radius: 6px;
        flex-shrink: 0;
    }

    .res-card__cod {
        font-size: .75rem;
        font-weight: 600;
        color: var(--text-muted);
        flex-shrink: 0;
    }

    .res-card__nome {
        font-size: .82rem;
        font-weight: 600;
        color: var(--text);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .res-card__body {
        padding: .85rem 1rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .55rem 1.25rem;
    }

    .res-info {
        display: flex;
        flex-direction: column;
        gap: .1rem;
    }

    .res-info--full { grid-column: 1 / -1; }

    .res-info__label {
        font-size: .68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-light);
    }

    .res-info__value {
        font-size: .78rem;
        color: var(--text);
        line-height: 1.45;
    }

    .res-info__value a { color: var(--primary); text-decoration: none; word-break: break-all; }
    .res-info__value a:hover { text-decoration: underline; }

    .badge {
        font-size: .68rem;
        font-weight: 600;
        padding: .15rem .45rem;
        border-radius: 999px;
        white-space: nowrap;
        display: inline-block;
    }

    .badge--blue    { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .badge--neutral { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    /* ─── Vigência tag ────────────────────────────────── */
    .vig-tag {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .72rem;
        font-weight: 500;
        padding: .15rem .5rem;
        border-radius: 999px;
    }

    .vig-tag svg {
        width: 10px; height: 10px;
        stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        flex-shrink: 0;
    }

    .vig-tag--active  { background: #f0fdf4; color: #16a34a; }
    .vig-tag--expired { background: #fef2f2; color: #dc2626; }
    .vig-tag--future  { background: #fffbeb; color: #d97706; }

    /* ─── Justificativa da IA ─────────────────────────── */
    .ia-justificativa {
        border: 1px solid #e9d5ff;
        border-radius: var(--radius-sm);
        overflow: hidden;
        margin-bottom: .75rem;
    }

    .ia-justificativa__header {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem .85rem;
        background: #faf5ff;
        border-bottom: 1px solid #e9d5ff;
        font-size: .75rem;
        font-weight: 700;
        color: #7c3aed;
    }

    .ia-justificativa__header svg {
        width: 13px; height: 13px;
        stroke: #7c3aed; fill: none;
        stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }

    .ia-justificativa__text {
        padding: .85rem;
        font-size: .78rem;
        color: var(--text);
        line-height: 1.6;
    }

    /* ─── Aviso auditor ───────────────────────────────── */
    .ia-aviso {
        display: flex;
        gap: .55rem;
        align-items: flex-start;
        padding: .7rem .85rem;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: var(--radius-sm);
        font-size: .75rem;
        color: #92400e;
        line-height: 1.5;
    }

    .ia-aviso svg {
        width: 14px; height: 14px;
        stroke: #d97706; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .ia-aviso p { margin: 0; }

    /* ─── Responsivo ──────────────────────────────────── */
    @media (max-width: 1100px) {
        .ia-layout {
            grid-template-columns: 1fr;
        }

        .ia-result-col {
            position: static;
        }
    }

    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
        .page-header__inner { flex-direction: column; gap: .6rem; }
        .form-actions { flex-direction: column-reverse; }
        .btn-primary, .btn-secondary { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var form       = document.getElementById('iaForm');
    var submitBtn  = document.getElementById('submitBtn');
    var clearBtn   = document.getElementById('clearFormBtn');

    // Painéis de resultado
    var pPlaceholder = document.getElementById('resultPlaceholder');
    var pLoading     = document.getElementById('resultLoading');
    var pContent     = document.getElementById('resultContent');
    var pError       = document.getElementById('resultError');
    var resultBody   = document.getElementById('resultBody');
    var resultNcm    = document.getElementById('resultNcm');
    var errorMsg     = document.getElementById('errorMsg');

    // ── Toggle regime CST ↔ CSOSN ───────────────────────
    var regimeBtns  = document.querySelectorAll('.toggle-btn');
    var regimeInput = document.getElementById('regimeValue');
    var cstField    = document.getElementById('cstField');
    var csosnField  = document.getElementById('csosnField');

    regimeBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            regimeBtns.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            regimeInput.value = btn.dataset.value;

            if (btn.dataset.value === 'simples') {
                cstField.style.display   = 'none';
                csosnField.style.display = '';
            } else {
                cstField.style.display   = '';
                csosnField.style.display = 'none';
            }
        });
    });

    // ── Máscaras numéricas ───────────────────────────────
    function onlyDigits(el, max) {
        el.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, max);
        });
    }

    onlyDigits(document.getElementById('ncm'),   10);
    onlyDigits(document.getElementById('cest'),   9);
    onlyDigits(document.getElementById('cfop'),   5);
    onlyDigits(document.getElementById('cst'),    3);
    onlyDigits(document.getElementById('csosn'),  3);

    // Alíquotas: aceita vírgula/ponto decimal
    ['aliq_icms','aliq_ipi','aliq_pis','aliq_cofins'].forEach(function (id) {
        document.getElementById(id).addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9.,]/g, '');
        });
    });

    // ── Mostrar painel ───────────────────────────────────
    function showPanel(which) {
        pPlaceholder.style.display = which === 'placeholder' ? '' : 'none';
        pLoading.style.display     = which === 'loading'     ? '' : 'none';
        pContent.style.display     = which === 'content'     ? '' : 'none';
        pError.style.display       = which === 'error'       ? '' : 'none';
    }

    // ── Loading do botão ─────────────────────────────────
    function setLoading(on) {
        submitBtn.disabled = on;
        submitBtn.querySelector('.btn-label').style.display   = on ? 'none' : 'inline-flex';
        submitBtn.querySelector('.btn-loading').style.display = on ? 'inline-flex' : 'none';
    }

    // ── Limpar formulário ────────────────────────────────
    clearBtn.addEventListener('click', function () {
        form.reset();
        // Reseta regime para padrão
        regimeBtns.forEach(function (b) { b.classList.remove('active'); });
        regimeBtns[0].classList.add('active');
        regimeInput.value = 'normal';
        cstField.style.display   = '';
        csosnField.style.display = 'none';
        showPanel('placeholder');
    });

    // ── Submit ────────────────────────────────────────────
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validação básica
        var descricao = document.getElementById('descricao_produto').value.trim();
        var ncm       = document.getElementById('ncm').value.trim();
        var cfop      = document.getElementById('cfop').value.trim();

        if (!descricao) {
            document.getElementById('descricao_produto').focus();
            return;
        }
        if (!ncm) {
            document.getElementById('ncm').focus();
            return;
        }
        if (!cfop) {
            document.getElementById('cfop').focus();
            return;
        }

        // Monta payload
        var regime = regimeInput.value;
        var payload = {
            descricao_produto: descricao,
            ncm:               ncm,
            cest:              document.getElementById('cest').value.trim(),
            cfop:              cfop,
            regime:            regime,
            cst:               regime === 'normal'  ? document.getElementById('cst').value.trim()   : '',
            csosn:             regime === 'simples' ? document.getElementById('csosn').value.trim() : '',
            aliq_icms:         document.getElementById('aliq_icms').value.trim(),
            aliq_ipi:          document.getElementById('aliq_ipi').value.trim(),
            aliq_pis:          document.getElementById('aliq_pis').value.trim(),
            aliq_cofins:       document.getElementById('aliq_cofins').value.trim(),
            tem_st:            document.querySelector('input[name="tem_st"]:checked').value,
            tipo_operacao:     document.getElementById('tipo_operacao').value,
            destinacao:        document.getElementById('destinacao').value,
            uf_origem:         document.getElementById('uf_origem').value,
            uf_destino:        document.getElementById('uf_destino').value,
            observacoes:       document.getElementById('observacoes').value.trim(),
        };

        setLoading(true);
        showPanel('loading');

        // Scroll suave para o painel em mobile
        if (window.innerWidth <= 1100) {
            document.querySelector('.ia-result-col').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        var csrfToken  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var consultarUrl = '{{ route("consulta.ia.consultar") }}';

        fetch(consultarUrl, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  csrfToken,
                'Accept':        'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(function (res) { return res.json().then(function (d) { return { ok: res.ok, data: d }; }); })
        .then(function (resp) {
            var data = resp.data;

            if (!resp.ok || !data.encontrado) {
                errorMsg.innerHTML = data.mensagem || 'Erro ao consultar. Tente novamente.';
                showPanel('error');
                return;
            }

            resultNcm.textContent = payload.ncm;
            resultBody.innerHTML  = renderResultado(data);
            showPanel('content');
        })
        .catch(function () {
            errorMsg.innerHTML = 'Erro de conexão ao consultar o servidor. Tente novamente.';
            showPanel('error');
        })
        .finally(function () {
            setLoading(false);
        });
    });

    // ── Renderizar resultado ─────────────────────────────
    function renderResultado(data) {
        var r   = data.resultado;
        var via = data.via;

        var html = [];

        // Badge de origem (base local vs IA)
        if (via === 'base_local') {
            html.push(
                '<div class="via-badge via-badge--local">',
                  '<svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                  'Resultado direto da base local',
                '</div>'
            );
        } else {
            html.push(
                '<div class="via-badge via-badge--ia">',
                  '<svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2z" fill="currentColor"/></svg>',
                  'Classificação sugerida pela IA',
                  data.total_candidatos ? ' <span class="via-candidates">(entre ' + data.total_candidatos + ' candidatas)</span>' : '',
                '</div>'
            );
        }

        html.push('<p class="via-msg">' + escHtml(data.mensagem) + '</p>');

        // Card principal com resultado
        html.push('<div class="res-card">');

        // Header do card
        html.push(
            '<div class="res-card__header">',
              '<span class="res-card__cst">CST ' + escHtml(r.cst) + '</span>',
              '<span class="res-card__cod">' + escHtml(r.cod_class_trib) + '</span>',
              '<span class="res-card__nome">' + escHtml(r.nome_reduzido || r.nome || '—') + '</span>',
            '</div>'
        );

        // Body do card
        html.push('<div class="res-card__body">');

        // Documentos fiscais
        var docs = [];
        if (r.ind_nfe  == 1) docs.push('<span class="badge badge--blue">NF-e</span>');
        if (r.ind_nfce == 1) docs.push('<span class="badge badge--blue">NFC-e</span>');
        if (r.ind_cte  == 1) docs.push('<span class="badge badge--neutral">CT-e</span>');
        if (docs.length) {
            html.push(infoRow('Documentos fiscais', docs.join(' ')));
        }

        // Vigência
        html.push(infoRow('Vigência', vigenciaTag(r.dth_ini_vig, r.dth_fim_vig)));

        // Tipo de alíquota
        if (r.tipo_aliquota) html.push(infoRow('Tipo de alíquota', escHtml(r.tipo_aliquota)));

        // Reduções
        var redIbs = parseFloat(r.aliq_red_ibs) || 0;
        var redCbs = parseFloat(r.aliq_red_cbs) || 0;
        if (redIbs > 0) html.push(infoRow('Redução IBS', redIbs.toFixed(2).replace('.', ',') + '%'));
        if (redCbs > 0) html.push(infoRow('Redução CBS', redCbs.toFixed(2).replace('.', ',') + '%'));

        // Descrição CST
        if (r.descricao_cst) html.push(infoRowFull('Descrição do CST', escHtml(r.descricao_cst)));

        // Descrição
        if (r.descricao) html.push(infoRowFull('Descrição da classificação', escHtml(r.descricao)));

        // Condição
        if (r.desc_condicao) html.push(infoRowFull('Condição de aplicação', escHtml(r.desc_condicao)));

        // Item do anexo
        if (r.desc_item_anexo) html.push(infoRowFull('Item no anexo', escHtml(r.desc_item_anexo)));

        // Anexo
        if (r.desc_anexo) html.push(infoRowFull('Descrição do anexo', escHtml(r.desc_anexo)));

        // Exceção
        if (r.desc_excecao) html.push(infoRowFull('Exceção', escHtml(r.desc_excecao)));

        // Link
        if (r.link) html.push(infoRowFull('Legislação', '<a href="' + escHtml(r.link) + '" target="_blank" rel="noopener">' + escHtml(r.link) + '</a>'));

        html.push('</div>'); // res-card__body
        html.push('</div>'); // res-card

        // Justificativa da IA
        if (via === 'ia' && data.justificativa) {
            html.push(
                '<div class="ia-justificativa">',
                  '<div class="ia-justificativa__header">',
                    '<svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
                    'Justificativa da IA',
                  '</div>',
                  '<p class="ia-justificativa__text">' + escHtml(data.justificativa) + '</p>',
                '</div>'
            );
        }

        // Aviso se via IA
        if (via === 'ia') {
            html.push(
                '<div class="ia-aviso">',
                  '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
                  '<p>Esta é uma <strong>sugestão da IA</strong> e deve ser validada pelo auditor. Confira a justificativa e verifique se a classificação é compatível com a operação.</p>',
                '</div>'
            );
        }

        return html.join('');
    }

    // ── Helpers de renderização ───────────────────────────
    function infoRow(label, value) {
        return '<div class="res-info"><span class="res-info__label">' + label + '</span><span class="res-info__value">' + value + '</span></div>';
    }

    function infoRowFull(label, value) {
        return '<div class="res-info res-info--full"><span class="res-info__label">' + label + '</span><span class="res-info__value">' + value + '</span></div>';
    }

    function formatDate(str) {
        if (!str) return null;
        var d = new Date(str);
        if (isNaN(d)) return str;
        return d.toLocaleDateString('pt-BR');
    }

    function vigenciaTag(ini, fim) {
        var hoje = new Date();
        hoje.setHours(0,0,0,0);
        var inicio = ini ? new Date(ini) : null;
        var fim_   = fim ? new Date(fim) : null;

        if (fim_ && fim_ < hoje) {
            return '<span class="vig-tag vig-tag--expired"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>Encerrada em ' + formatDate(fim) + '</span>';
        }
        if (inicio && inicio > hoje) {
            return '<span class="vig-tag vig-tag--future"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>A partir de ' + formatDate(ini) + '</span>';
        }
        var desde = ini ? ' desde ' + formatDate(ini) : '';
        return '<span class="vig-tag vig-tag--active"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Em vigor' + desde + '</span>';
    }

    function escHtml(str) {
        if (!str) return '';
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

}());
</script>
@endpush
