@extends('layouts.app')

@section('title', 'Consulta de NCM')

@section('content')

<div class="page-header">
    <h1 class="page-title">Consulta de NCM</h1>
    <p class="page-subtitle">Informe o código NCM para verificar as classificações tributárias vinculadas.</p>
</div>

{{-- Formulário de busca --}}
<div class="search-card">
    <div class="search-bar">
        <div class="search-input-wrap">
            <span class="search-icon">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input
                type="text"
                id="ncmInput"
                placeholder="Digite o código NCM (ex: 10062010)"
                maxlength="10"
                autocomplete="off"
                inputmode="numeric"
            >
            <button type="button" id="clearBtn" class="search-clear" title="Limpar" style="display:none">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <button type="button" id="searchBtn" class="search-submit">
            <span class="search-submit__label">Consultar</span>
            <span class="search-submit__loading" style="display:none">
                <span class="search-spinner"></span>
            </span>
        </button>
    </div>
    <p class="search-hint">Apenas dígitos — pontos e traços são ignorados automaticamente.</p>
</div>

{{-- Área de resultado --}}
<div id="resultArea" style="display:none">

    {{-- Estado: não encontrado --}}
    <div id="resultNotFound" style="display:none">
        <div class="result-empty">
            <div class="result-empty__icon">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
            </div>
            <p class="result-empty__title">Nenhum resultado encontrado</p>
            <p class="result-empty__msg" id="notFoundMsg"></p>
        </div>
    </div>

    {{-- Estado: encontrado --}}
    <div id="resultFound" style="display:none">

        {{-- Cabeçalho do resultado --}}
        <div class="result-header">
            <div class="result-header__info">
                <div class="result-ncm-badge" id="resultNcmBadge"></div>
                <span class="result-count" id="resultCount"></span>
            </div>
        </div>

        {{-- Cards de resultado --}}
        <div id="resultCards" class="result-cards"></div>

    </div>

</div>

@endsection

@push('styles')
<style>
    /* ─── Search card ─────────────────────────────────── */
    .search-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        max-width: 700px;
    }

    .search-bar {
        display: flex;
        gap: .6rem;
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
        display: flex;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: .85rem;
        color: var(--text-muted);
        pointer-events: none;
        display: flex;
    }

    .search-icon svg {
        width: 17px; height: 17px;
        stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }

    #ncmInput {
        width: 100%;
        padding: .7rem .7rem .7rem 2.5rem;
        font-size: 1rem;
        font-family: 'Courier New', monospace;
        font-weight: 600;
        letter-spacing: .08em;
        color: var(--text);
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        outline: none;
        transition: border-color .18s, box-shadow .18s, background .18s;
    }

    #ncmInput:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(37,99,235,.15);
    }

    .search-clear {
        position: absolute;
        right: .6rem;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
        padding: .25rem;
        border-radius: 4px;
        display: flex;
        transition: color .18s, background .18s;
    }

    .search-clear:hover { color: var(--text); background: var(--border); }

    .search-clear svg {
        width: 14px; height: 14px;
        stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round;
    }

    .search-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        padding: .7rem 1.5rem;
        font-family: inherit;
        font-size: .9rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(37,99,235,.3);
        transition: box-shadow .18s, transform .18s;
        min-width: 110px;
    }

    .search-submit:hover { box-shadow: 0 4px 12px rgba(37,99,235,.4); }
    .search-submit:active { transform: scale(.97); }
    .search-submit:disabled { opacity: .55; cursor: not-allowed; transform: none; }

    .search-spinner {
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .6s linear infinite;
        display: block;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .search-hint {
        font-size: .75rem;
        color: var(--text-light);
        margin-top: .6rem;
    }

    /* ─── Resultado vazio ─────────────────────────────── */
    .result-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .6rem;
        padding: 3rem 1rem;
        text-align: center;
    }

    .result-empty__icon {
        width: 56px; height: 56px;
        background: #fef2f2;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }

    .result-empty__icon svg {
        width: 26px; height: 26px;
        stroke: #ef4444; fill: none;
        stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }

    .result-empty__title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--text);
    }

    .result-empty__msg {
        font-size: .83rem;
        color: var(--text-muted);
        max-width: 420px;
        line-height: 1.55;
    }

    /* ─── Cabeçalho resultado ─────────────────────────── */
    .result-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .result-header__info {
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .result-ncm-badge {
        font-family: 'Courier New', monospace;
        font-size: .95rem;
        font-weight: 700;
        letter-spacing: .1em;
        background: #eff6ff;
        color: var(--primary);
        border: 1px solid #bfdbfe;
        padding: .3rem .8rem;
        border-radius: 999px;
    }

    .result-count {
        font-size: .82rem;
        color: var(--text-muted);
    }

    /* ─── Cards de resultado ──────────────────────────── */
    .result-cards {
        display: flex;
        flex-direction: column;
        gap: .85rem;
    }

    .result-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        animation: cardIn .25s cubic-bezier(.16,1,.3,1);
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .result-card__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: .9rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg);
    }

    .result-card__title-group {
        display: flex;
        align-items: center;
        gap: .65rem;
        min-width: 0;
    }

    .result-card__cst {
        font-family: 'Courier New', monospace;
        font-size: .78rem;
        font-weight: 700;
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
        padding: .2rem .55rem;
        border-radius: 6px;
        flex-shrink: 0;
        letter-spacing: .05em;
    }

    .result-card__cod {
        font-size: .78rem;
        font-weight: 600;
        color: var(--text-muted);
        flex-shrink: 0;
    }

    .result-card__nome {
        font-size: .88rem;
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .result-card__badges {
        display: flex;
        align-items: center;
        gap: .4rem;
        flex-shrink: 0;
    }

    .badge {
        font-size: .7rem;
        font-weight: 600;
        padding: .2rem .55rem;
        border-radius: 999px;
        white-space: nowrap;
    }

    .badge--green    { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge--red      { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge--amber    { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge--blue     { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .badge--neutral  { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    .result-card__body {
        padding: 1rem 1.25rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .65rem 2rem;
    }

    .info-row {
        display: flex;
        flex-direction: column;
        gap: .15rem;
    }

    .info-row--full {
        grid-column: 1 / -1;
    }

    .info-label {
        font-size: .7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-light);
    }

    .info-value {
        font-size: .83rem;
        color: var(--text);
        line-height: 1.45;
    }

    .info-value--empty { color: var(--text-light); font-style: italic; }

    .info-value a {
        color: var(--primary);
        text-decoration: none;
        word-break: break-all;
    }

    .info-value a:hover { text-decoration: underline; }

    /* ─── Vigência ────────────────────────────────────── */
    .vig-tag {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .75rem;
        font-weight: 500;
        padding: .2rem .6rem;
        border-radius: 999px;
    }

    .vig-tag svg {
        width: 11px; height: 11px;
        stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        flex-shrink: 0;
    }

    .vig-tag--active  { background: #f0fdf4; color: #16a34a; }
    .vig-tag--expired { background: #fef2f2; color: #dc2626; }
    .vig-tag--future  { background: #fffbeb; color: #d97706; }

    @media (max-width: 640px) {
        .search-bar { flex-direction: column; }
        .search-submit { width: 100%; }
        .result-card__body { grid-template-columns: 1fr; }
        .result-card__nome { white-space: normal; }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var input     = document.getElementById('ncmInput');
    var clearBtn  = document.getElementById('clearBtn');
    var searchBtn = document.getElementById('searchBtn');
    var resultArea     = document.getElementById('resultArea');
    var resultNotFound = document.getElementById('resultNotFound');
    var resultFound    = document.getElementById('resultFound');
    var resultCards    = document.getElementById('resultCards');
    var resultNcmBadge = document.getElementById('resultNcmBadge');
    var resultCount    = document.getElementById('resultCount');
    var notFoundMsg    = document.getElementById('notFoundMsg');

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var buscarUrl = '{{ route("consulta.ncm.buscar") }}';

    // ── Helpers ─────────────────────────────────────────
    function setLoading(on) {
        searchBtn.disabled = on;
        searchBtn.querySelector('.search-submit__label').style.display = on ? 'none' : 'inline';
        searchBtn.querySelector('.search-submit__loading').style.display = on ? 'flex' : 'none';
    }

    function showArea(which) {
        resultArea.style.display     = 'block';
        resultNotFound.style.display = which === 'notfound' ? 'block' : 'none';
        resultFound.style.display    = which === 'found'    ? 'block' : 'none';
    }

    function hideArea() {
        resultArea.style.display = 'none';
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
            return '<span class="vig-tag vig-tag--future"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Vigência a partir de ' + formatDate(ini) + '</span>';
        }
        var desde = ini ? ' desde ' + formatDate(ini) : '';
        return '<span class="vig-tag vig-tag--active"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Em vigor' + desde + '</span>';
    }

    function permissaoBadge(tipo) {
        if (!tipo) return '';
        var map = {
            'PERMITIDO': 'badge--green',
            'VEDADO':    'badge--red',
        };
        var cls = map[tipo.toUpperCase()] || 'badge--neutral';
        return '<span class="badge ' + cls + '">' + tipo + '</span>';
    }

    function escHtml(str) {
        if (!str) return '';
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function val(v, fallback) {
        return (v !== null && v !== undefined && v !== '') ? v : (fallback || null);
    }

    // ── Renderizar cards ─────────────────────────────────
    function renderCards(resultados, ncm) {
        resultNcmBadge.textContent = ncm;
        resultCount.textContent    = resultados.length === 1
            ? '1 classificação encontrada'
            : resultados.length + ' classificações encontradas';

        resultCards.innerHTML = resultados.map(function(r, idx) {
            var nomeReduzido  = val(r.nome_reduzido) || val(r.nome) || '—';
            var descCondicao  = val(r.desc_condicao);
            var descItemAnexo = val(r.desc_item_anexo);
            var descAnexo     = val(r.desc_anexo);
            var descExcecao   = val(r.desc_excecao);
            var descricao     = val(r.descricao);
            var temCtJoin     = val(r.descricao_cst) !== null;

            // Badges de documentos fiscais (vindos do join)
            var docsBadges = '';
            if (r.ind_nfe  == 1) docsBadges += '<span class="badge badge--blue">NF-e</span>';
            if (r.ind_nfce == 1) docsBadges += '<span class="badge badge--blue">NFC-e</span>';
            if (r.ind_cte  == 1) docsBadges += '<span class="badge badge--neutral">CT-e</span>';

            // Alíquotas de redução (só exibe se > 0)
            var aliqInfo = '';
            if (temCtJoin) {
                var redIbs = parseFloat(r.aliq_red_ibs) || 0;
                var redCbs = parseFloat(r.aliq_red_cbs) || 0;
                if (redIbs > 0 || redCbs > 0) {
                    aliqInfo = [
                        '<div class="info-row">',
                          '<span class="info-label">Redução IBS</span>',
                          '<span class="info-value">' + redIbs.toFixed(2).replace('.', ',') + '%</span>',
                        '</div>',
                        '<div class="info-row">',
                          '<span class="info-label">Redução CBS</span>',
                          '<span class="info-value">' + redCbs.toFixed(2).replace('.', ',') + '%</span>',
                        '</div>',
                    ].join('');
                }
            }

            return [
                '<div class="result-card" style="animation-delay:' + (idx * 40) + 'ms">',

                  // Header
                  '<div class="result-card__header">',
                    '<div class="result-card__title-group">',
                      '<span class="result-card__cst">CST ' + escHtml(r.cst) + '</span>',
                      '<span class="result-card__cod">' + escHtml(r.cod_class_trib) + '</span>',
                      '<span class="result-card__nome">' + escHtml(nomeReduzido) + '</span>',
                    '</div>',
                    '<div class="result-card__badges">',
                      docsBadges,
                      r.nro_anexo !== null ? '<span class="badge badge--amber">Anexo ' + r.nro_anexo + '</span>' : '',
                    '</div>',
                  '</div>',

                  // Body
                  '<div class="result-card__body">',

                    // Vigência do NCM no anexo
                    '<div class="info-row">',
                      '<span class="info-label">Vigência no anexo</span>',
                      '<span class="info-value">' + vigenciaTag(r.dth_ini_vig, r.dth_fim_vig) + '</span>',
                    '</div>',

                    // Tipo de alíquota (do join)
                    temCtJoin && val(r.tipo_aliquota) ? [
                      '<div class="info-row">',
                        '<span class="info-label">Tipo de alíquota</span>',
                        '<span class="info-value">' + escHtml(r.tipo_aliquota) + '</span>',
                      '</div>',
                    ].join('') : '',

                    // Percentuais de redução
                    aliqInfo,

                    // Condição
                    descCondicao ? [
                      '<div class="info-row info-row--full">',
                        '<span class="info-label">Condição de aplicação</span>',
                        '<span class="info-value">' + escHtml(descCondicao) + '</span>',
                      '</div>',
                    ].join('') : '',

                    // Descrição completa da classificação (do join)
                    temCtJoin && descricao ? [
                      '<div class="info-row info-row--full">',
                        '<span class="info-label">Descrição da classificação</span>',
                        '<span class="info-value">' + escHtml(descricao) + '</span>',
                      '</div>',
                    ].join('') : '',

                    // Descrição do item no anexo
                    descItemAnexo ? [
                      '<div class="info-row info-row--full">',
                        '<span class="info-label">Descrição do item no anexo</span>',
                        '<span class="info-value">' + escHtml(descItemAnexo) + '</span>',
                      '</div>',
                    ].join('') : '',

                    // Descrição do anexo
                    descAnexo ? [
                      '<div class="info-row info-row--full">',
                        '<span class="info-label">Descrição do anexo</span>',
                        '<span class="info-value">' + escHtml(descAnexo) + '</span>',
                      '</div>',
                    ].join('') : '',

                    // Exceção
                    descExcecao ? [
                      '<div class="info-row info-row--full">',
                        '<span class="info-label">Exceção</span>',
                        '<span class="info-value">' + escHtml(descExcecao) + '</span>',
                      '</div>',
                    ].join('') : '',

                    // Link da legislação (do join)
                    temCtJoin && val(r.link) ? [
                      '<div class="info-row info-row--full">',
                        '<span class="info-label">Legislação</span>',
                        '<span class="info-value"><a href="' + escHtml(r.link) + '" target="_blank" rel="noopener">' + escHtml(r.link) + '</a></span>',
                      '</div>',
                    ].join('') : '',

                  '</div>',
                '</div>',
            ].join('');
        }).join('');
    }

    // ── Buscar ───────────────────────────────────────────
    function buscar() {
        var ncm = input.value.trim();
        if (!ncm) {
            input.focus();
            return;
        }

        setLoading(true);
        hideArea();

        fetch(buscarUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ ncm: ncm }),
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.encontrado) {
                renderCards(data.resultados, data.ncm);
                showArea('found');
            } else {
                notFoundMsg.innerHTML = data.mensagem || 'Nenhum resultado encontrado.';
                showArea('notfound');
            }
        })
        .catch(function() {
            notFoundMsg.innerHTML = 'Erro ao realizar a consulta. Tente novamente.';
            showArea('notfound');
        })
        .finally(function() {
            setLoading(false);
        });
    }

    // ── Eventos ──────────────────────────────────────────
    searchBtn.addEventListener('click', buscar);

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') buscar();
    });

    input.addEventListener('input', function() {
        // Só permite dígitos
        this.value = this.value.replace(/[^0-9]/g, '');
        clearBtn.style.display = this.value ? 'flex' : 'none';
        if (!this.value) hideArea();
    });

    clearBtn.addEventListener('click', function() {
        input.value = '';
        clearBtn.style.display = 'none';
        hideArea();
        input.focus();
    });

}());
</script>
@endpush
