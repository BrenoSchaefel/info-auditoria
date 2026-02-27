@extends('layouts.app')

@section('title', 'Importação')

@section('content')

<div class="page-header">
    <h1 class="page-title">Importação de Dados</h1>
    <p class="page-subtitle">Selecione o tipo de importação que deseja realizar.</p>
</div>

<div class="import-cards-grid">

    <a href="{{ route('importacao.ncm-classificacao.index') }}" class="import-type-card">
        <div class="import-type-card__icon import-type-card__icon--blue">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
        </div>
        <div class="import-type-card__body">
            <h3 class="import-type-card__title">NCM × Classificação Tributária</h3>
            <p class="import-type-card__desc">
                Importe os códigos NCM vinculados a cada classificação tributária (CST), com condições, anexos e vigência.
            </p>
        </div>
        <div class="import-type-card__arrow">
            <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </div>
    </a>

    <a href="{{ route('importacao.classificacao-tributaria.index') }}" class="import-type-card">
        <div class="import-type-card__icon import-type-card__icon--violet">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div class="import-type-card__body">
            <h3 class="import-type-card__title">Classificação Tributária</h3>
            <p class="import-type-card__desc">
                Importe as classificações tributárias com CST, descrição, alíquotas, indicadores e vigência a partir de um arquivo CSV.
            </p>
        </div>
        <div class="import-type-card__arrow">
            <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </div>
    </a>

    {{-- Futuras importações ficarão aqui --}}

</div>

@endsection

@push('styles')
<style>
    .import-cards-grid {
        display: flex;
        flex-direction: column;
        gap: .75rem;
        max-width: 680px;
    }

    .import-type-card {
        display: flex;
        align-items: center;
        gap: 1.1rem;
        padding: 1.1rem 1.25rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        text-decoration: none;
        transition: box-shadow var(--transition), border-color var(--transition), transform var(--transition);
    }

    .import-type-card:hover {
        box-shadow: var(--shadow-md);
        border-color: #bfdbfe;
        transform: translateY(-1px);
    }

    .import-type-card__icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .import-type-card__icon svg {
        width: 22px; height: 22px;
        stroke: currentColor; fill: none;
        stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }

    .import-type-card__icon--blue   { background: #eff6ff; color: #2563eb; }
    .import-type-card__icon--violet { background: #f5f3ff; color: #7c3aed; }

    .import-type-card__body { flex: 1; min-width: 0; }

    .import-type-card__title {
        font-size: .92rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: .25rem;
    }

    .import-type-card__desc {
        font-size: .8rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .import-type-card__arrow {
        color: var(--text-light);
        flex-shrink: 0;
    }

    .import-type-card__arrow svg {
        width: 18px; height: 18px;
        stroke: currentColor; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
</style>
@endpush
