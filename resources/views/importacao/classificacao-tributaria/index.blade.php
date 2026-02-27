@extends('layouts.app')

@section('title', 'Importar Classificação Tributária')

@section('content')

<div class="page-header">
    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.2rem">
        <a href="{{ route('importacao.index') }}" class="breadcrumb-back">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <h1 class="page-title" style="margin:0">Classificação Tributária</h1>
    </div>
    <p class="page-subtitle" style="padding-left:1.8rem">
        Importe as classificações tributárias a partir de um arquivo CSV.
    </p>
</div>

<div class="import-layout">

    {{-- Coluna principal --}}
    <div class="import-main">

        @if (session('sucesso'))
            <div class="import-alert import-alert--success">
                <div class="import-alert__icon">
                    <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="import-alert__body">
                    <strong>Importação concluída!</strong>
                    <span>{{ session('sucesso') }}</span>
                </div>
                <button class="import-alert__close" onclick="this.closest('.import-alert').remove()">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        @endif

        @if (session('erro'))
            <div class="import-alert import-alert--danger">
                <div class="import-alert__icon">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div class="import-alert__body">
                    <strong>Erro na importação</strong>
                    <span>{{ session('erro') }}</span>
                </div>
                <button class="import-alert__close" onclick="this.closest('.import-alert').remove()">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <h2 class="section-title" style="margin-bottom:.3rem">Arquivo de importação</h2>
                <p style="font-size:.83rem;color:var(--text-muted);margin-bottom:1.5rem;line-height:1.55">
                    O arquivo CSV deve conter <strong>cabeçalho na primeira linha</strong> e os campos separados por vírgula ou ponto-e-vírgula. Todos os registros existentes serão substituídos.
                </p>

                <form
                    method="POST"
                    action="{{ route('importacao.classificacao-tributaria.importar') }}"
                    enctype="multipart/form-data"
                    id="importForm"
                >
                    @csrf

                    <div class="drop-zone" id="dropZone">
                        <input
                            type="file"
                            name="arquivo"
                            id="arquivoInput"
                            accept=".csv,text/csv,text/plain"
                            class="drop-zone__input"
                        >

                        <div id="dzIdle">
                            <div class="drop-zone__icon">
                                <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </div>
                            <p class="drop-zone__title">Arraste o arquivo aqui</p>
                            <p class="drop-zone__sub">ou <span class="drop-zone__link">clique para selecionar</span></p>
                            <p class="drop-zone__hint">Somente <strong>.csv</strong> · Máximo 20 MB</p>
                        </div>

                        <div id="dzPreview" style="display:none">
                            <div class="file-preview">
                                <div class="file-preview__icon">
                                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/><line x1="9" y1="11" x2="15" y2="11"/></svg>
                                </div>
                                <div class="file-preview__info">
                                    <span class="file-preview__name" id="fileName"></span>
                                    <span class="file-preview__size" id="fileSize"></span>
                                </div>
                                <button type="button" class="file-preview__remove" id="removeFile" title="Remover arquivo">
                                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    @error('arquivo')
                        <p class="field-error">{{ $message }}</p>
                    @enderror

                    <div class="warning-box">
                        <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <span>Esta operação <strong>apaga todos os registros existentes</strong> e os substitui pelo conteúdo do arquivo. A ação não pode ser desfeita.</span>
                    </div>

                    <div style="display:flex;align-items:center;gap:.9rem;margin-top:1.5rem">
                        <button type="submit" class="btn-import" id="btnImport" disabled>
                            <span class="btn-import__label">
                                <svg viewBox="0 0 24 24"><polyline points="8 17 12 21 16 17"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"/></svg>
                                Importar
                            </span>
                            <span class="btn-import__loading" style="display:none">
                                <span class="btn-spinner-dark"></span>
                                Importando…
                            </span>
                        </button>
                        <span class="import-hint" id="importHint">Selecione um arquivo para continuar</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Coluna lateral --}}
    <div class="import-side">

        <div class="card">
            <div class="card-body">
                <h3 class="side-card-title">Status atual</h3>

                <div class="status-stat">
                    <span class="status-stat__label">Registros na base</span>
                    <span class="status-stat__value">{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="status-stat">
                    <span class="status-stat__label">Última importação</span>
                    <span class="status-stat__value status-stat__value--sm">
                        {{ $ultima ? \Carbon\Carbon::parse($ultima)->format('d/m/Y H:i') : 'Nunca' }}
                    </span>
                </div>

                <div class="status-badge {{ $total > 0 ? 'status-badge--ok' : 'status-badge--empty' }}">
                    @if ($total > 0)
                        <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Base populada
                    @else
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Base vazia
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="side-card-title">Colunas esperadas</h3>
                <p style="font-size:.75rem;color:var(--text-muted);margin-bottom:.8rem;line-height:1.5">
                    O cabeçalho do CSV deve conter as colunas nesta ordem:
                </p>
                <div class="columns-list">
                    @foreach(\App\Models\ClassificacaoTributaria::$csvColunas as $i => $col)
                        <div class="col-item">
                            <span class="col-item__num">{{ $i + 1 }}</span>
                            <code>{{ $col }}</code>
                        </div>
                    @endforeach
                </div>
                <p style="font-size:.72rem;color:var(--text-light);margin-top:.8rem;line-height:1.5">
                    Delimitador detectado automaticamente (<code>,</code> ou <code>;</code>).
                </p>
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
    .breadcrumb-back {
        display: flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 6px;
        border: 1px solid var(--border); color: var(--text-muted);
        text-decoration: none; flex-shrink: 0;
        transition: background var(--transition), color var(--transition);
    }
    .breadcrumb-back:hover { background: var(--bg); color: var(--text); }
    .breadcrumb-back svg {
        width: 14px; height: 14px; stroke: currentColor; fill: none;
        stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round;
    }

    .import-layout {
        display: grid;
        grid-template-columns: 1fr 270px;
        gap: 1.25rem;
        align-items: start;
    }

    .import-alert {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: .9rem 1rem; border-radius: var(--radius-sm);
        margin-bottom: 1rem; font-size: .85rem;
    }
    .import-alert__icon svg {
        width: 18px; height: 18px; stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        flex-shrink: 0; margin-top: 1px;
    }
    .import-alert__body { flex: 1; display: flex; flex-direction: column; gap: .15rem; }
    .import-alert__body strong { font-weight: 600; }
    .import-alert__close {
        background: none; border: none; cursor: pointer;
        padding: 0; color: inherit; opacity: .6; flex-shrink: 0;
    }
    .import-alert__close:hover { opacity: 1; }
    .import-alert__close svg {
        width: 15px; height: 15px; stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round;
    }
    .import-alert--success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .import-alert--danger  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

    .drop-zone {
        position: relative; border: 2px dashed var(--border);
        border-radius: var(--radius); padding: 2.5rem 1.5rem;
        text-align: center; cursor: pointer;
        transition: border-color var(--transition), background var(--transition);
        background: var(--bg);
    }
    .drop-zone:hover, .drop-zone.drag-over { border-color: var(--primary); background: var(--primary-light); }
    .drop-zone__input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .drop-zone__icon {
        width: 52px; height: 52px; background: var(--surface);
        border: 1px solid var(--border); border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto .9rem; box-shadow: var(--shadow-sm);
    }
    .drop-zone__icon svg {
        width: 22px; height: 22px; stroke: var(--primary); fill: none;
        stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }
    .drop-zone__title { font-size: .92rem; font-weight: 600; color: var(--text); margin-bottom: .25rem; }
    .drop-zone__sub   { font-size: .83rem; color: var(--text-muted); }
    .drop-zone__link  { color: var(--primary); font-weight: 500; }
    .drop-zone__hint  { font-size: .75rem; color: var(--text-light); margin-top: .5rem; }

    .file-preview { display: flex; align-items: center; gap: .9rem; text-align: left; }
    .file-preview__icon {
        width: 44px; height: 44px; background: #f0fdf4; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .file-preview__icon svg {
        width: 20px; height: 20px; stroke: #16a34a; fill: none;
        stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }
    .file-preview__info { flex: 1; display: flex; flex-direction: column; gap: .15rem; min-width: 0; }
    .file-preview__name {
        font-size: .88rem; font-weight: 600; color: var(--text);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .file-preview__size { font-size: .75rem; color: var(--text-muted); }
    .file-preview__remove {
        background: none; border: 1px solid var(--border); border-radius: 6px;
        cursor: pointer; padding: .35rem; color: var(--text-muted);
        display: flex; align-items: center;
        transition: background var(--transition), color var(--transition);
        position: relative; z-index: 2;
    }
    .file-preview__remove:hover { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    .file-preview__remove svg {
        width: 14px; height: 14px; stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round;
    }

    .field-error { font-size: .78rem; color: #ef4444; margin-top: .4rem; }

    .warning-box {
        display: flex; align-items: flex-start; gap: .6rem;
        background: #fffbeb; border: 1px solid #fde68a;
        border-radius: var(--radius-sm); padding: .8rem 1rem;
        font-size: .82rem; color: #92400e;
        margin-top: 1.25rem; line-height: 1.5;
    }
    .warning-box svg {
        width: 16px; height: 16px; stroke: #d97706; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        flex-shrink: 0; margin-top: 1px;
    }

    .btn-import {
        display: inline-flex; align-items: center; gap: .5rem;
        font-family: inherit; font-size: .88rem; font-weight: 600; color: #fff;
        background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
        border: none; border-radius: var(--radius-sm); padding: .65rem 1.25rem;
        cursor: pointer;
        transition: opacity var(--transition), transform var(--transition), box-shadow var(--transition);
        box-shadow: 0 2px 8px rgba(37,99,235,.3); white-space: nowrap;
    }
    .btn-import:hover:not(:disabled) { box-shadow: 0 4px 12px rgba(37,99,235,.4); }
    .btn-import:active:not(:disabled) { transform: scale(.97); }
    .btn-import:disabled { opacity: .45; cursor: not-allowed; box-shadow: none; }
    .btn-import__label, .btn-import__loading { display: flex; align-items: center; gap: .45rem; }
    .btn-import__label svg {
        width: 16px; height: 16px; stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
    .btn-spinner-dark {
        width: 15px; height: 15px;
        border: 2px solid rgba(255,255,255,.35); border-top-color: #fff;
        border-radius: 50%; animation: spin .6s linear infinite; flex-shrink: 0;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .import-hint { font-size: .78rem; color: var(--text-light); }

    .side-card-title { font-size: .88rem; font-weight: 700; color: var(--text); margin-bottom: 1rem; }

    .status-stat {
        display: flex; align-items: center; justify-content: space-between;
        padding: .55rem 0; border-bottom: 1px solid var(--border); gap: .5rem;
    }
    .status-stat:last-of-type { border-bottom: none; }
    .status-stat__label { font-size: .8rem; color: var(--text-muted); }
    .status-stat__value { font-size: .95rem; font-weight: 700; color: var(--text); }
    .status-stat__value--sm { font-size: .82rem; font-weight: 500; }

    .status-badge {
        display: flex; align-items: center; gap: .45rem;
        font-size: .78rem; font-weight: 600;
        padding: .45rem .7rem; border-radius: 999px;
        margin-top: 1rem; width: fit-content;
    }
    .status-badge svg {
        width: 13px; height: 13px; stroke: currentColor; fill: none;
        stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round;
    }
    .status-badge--ok    { background: #f0fdf4; color: #16a34a; }
    .status-badge--empty { background: #fef2f2; color: #dc2626; }

    .columns-list {
        display: flex; flex-direction: column; gap: .3rem;
        max-height: 280px; overflow-y: auto;
        scrollbar-width: thin; scrollbar-color: var(--border) transparent;
    }
    .col-item { display: flex; align-items: center; gap: .5rem; }
    .col-item__num {
        font-size: .68rem; font-weight: 600; color: var(--text-light);
        min-width: 18px; text-align: right;
    }
    .col-item code {
        font-family: 'Courier New', monospace; font-size: .72rem;
        background: #f1f5f9; color: var(--primary);
        padding: .1rem .35rem; border-radius: 4px;
    }

    code {
        font-family: 'Courier New', monospace; font-size: .78rem;
        background: #f1f5f9; padding: .1rem .35rem;
        border-radius: 4px; color: var(--primary);
    }

    @media (max-width: 900px) {
        .import-layout { grid-template-columns: 1fr; }
        .import-side { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    }
    @media (max-width: 600px) {
        .import-side { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
    var dropZone   = document.getElementById('dropZone');
    var input      = document.getElementById('arquivoInput');
    var dzIdle     = document.getElementById('dzIdle');
    var dzPreview  = document.getElementById('dzPreview');
    var fileName   = document.getElementById('fileName');
    var fileSize   = document.getElementById('fileSize');
    var removeBtn  = document.getElementById('removeFile');
    var btnImport  = document.getElementById('btnImport');
    var importHint = document.getElementById('importHint');
    var form       = document.getElementById('importForm');

    function formatBytes(b) {
        if (b < 1024)    return b + ' B';
        if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
        return (b / 1048576).toFixed(2) + ' MB';
    }

    function showFile(file) {
        fileName.textContent    = file.name;
        fileSize.textContent    = formatBytes(file.size);
        dzIdle.style.display    = 'none';
        dzPreview.style.display = 'block';
        btnImport.disabled      = false;
        importHint.textContent  = 'Pronto para importar';
    }

    function clearFile() {
        input.value             = '';
        dzIdle.style.display    = 'block';
        dzPreview.style.display = 'none';
        btnImport.disabled      = true;
        importHint.textContent  = 'Selecione um arquivo para continuar';
    }

    input.addEventListener('change', function() {
        if (this.files && this.files[0]) showFile(this.files[0]);
    });

    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        clearFile();
    });

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault(); this.classList.add('drag-over');
    });
    dropZone.addEventListener('dragleave', function() {
        this.classList.remove('drag-over');
    });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault(); this.classList.remove('drag-over');
        var file = e.dataTransfer.files[0];
        if (file) {
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            showFile(file);
        }
    });

    form.addEventListener('submit', function() {
        if (!btnImport.disabled) {
            btnImport.querySelector('.btn-import__label').style.display   = 'none';
            btnImport.querySelector('.btn-import__loading').style.display = 'flex';
            btnImport.disabled = true;
        }
    });
</script>
@endpush
