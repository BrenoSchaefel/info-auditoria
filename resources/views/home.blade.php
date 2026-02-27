@extends('layouts.app')

@section('title', 'Visão Geral')

@section('content')

<div class="page-header">
    <h1 class="page-title">Visão Geral</h1>
    <p class="page-subtitle">Bem-vindo, {{ Auth::user()->name }}. Acompanhe o painel de auditoria abaixo.</p>
</div>

{{-- KPI Cards --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon kpi-icon--blue">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div class="kpi-body">
            <span class="kpi-label">Relatórios</span>
            <span class="kpi-value">—</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon kpi-icon--violet">
            <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div class="kpi-body">
            <span class="kpi-label">Análises</span>
            <span class="kpi-value">—</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon kpi-icon--amber">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <div class="kpi-body">
            <span class="kpi-label">Consultas</span>
            <span class="kpi-value">—</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon kpi-icon--green">
            <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="kpi-body">
            <span class="kpi-label">Concluídos</span>
            <span class="kpi-value">—</span>
        </div>
    </div>
</div>

{{-- Linha principal --}}
<div class="dashboard-row">

    {{-- Gráfico placeholder --}}
    <div class="card dashboard-chart-card">
        <div class="card-body">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Atividade Recente</h2>
                    <p class="section-subtitle">Evolução dos registros ao longo do tempo</p>
                </div>
                <div class="period-tabs">
                    <button class="period-tab active">7d</button>
                    <button class="period-tab">30d</button>
                    <button class="period-tab">90d</button>
                </div>
            </div>
            <div class="chart-placeholder">
                <svg viewBox="0 0 480 160" preserveAspectRatio="none" class="chart-lines">
                    <defs>
                        <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#2563eb" stop-opacity=".15"/>
                            <stop offset="100%" stop-color="#2563eb" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0 120 C40 100 80 80 120 90 C160 100 200 60 240 50 C280 40 320 70 360 55 C400 40 440 30 480 20 L480 160 L0 160 Z"
                          fill="url(#chartGrad)"/>
                    <path d="M0 120 C40 100 80 80 120 90 C160 100 200 60 240 50 C280 40 320 70 360 55 C400 40 440 30 480 20"
                          fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                <div class="chart-empty-label">Dados indisponíveis</div>
            </div>
        </div>
    </div>

    {{-- Atividade rápida --}}
    <div class="card dashboard-activity-card">
        <div class="card-body">
            <div class="section-header" style="margin-bottom:1.1rem">
                <h2 class="section-title">Últimas Atividades</h2>
            </div>
            <div class="activity-list">
                @foreach(range(1,4) as $i)
                <div class="activity-item">
                    <div class="activity-dot"></div>
                    <div class="activity-info">
                        <span class="activity-title">Nenhum dado disponível</span>
                        <span class="activity-time">—</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- Linha secundária --}}
<div class="dashboard-row dashboard-row--secondary">

    {{-- Tabela placeholder --}}
    <div class="card" style="flex:1">
        <div class="card-body">
            <div class="section-header" style="margin-bottom:1.25rem">
                <div>
                    <h2 class="section-title">Registros Recentes</h2>
                    <p class="section-subtitle">Últimas entradas do sistema</p>
                </div>
                <button class="btn-ghost">
                    <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    Ver todos
                </button>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-empty">
                            <td colspan="5">
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                    <span>Nenhum registro encontrado</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Legenda / resumo --}}
    <div class="card dashboard-summary-card">
        <div class="card-body">
            <h2 class="section-title" style="margin-bottom:1.1rem">Resumo</h2>
            <div class="summary-list">
                <div class="summary-item">
                    <div class="summary-dot" style="background:#2563eb"></div>
                    <span class="summary-label">Pendentes</span>
                    <span class="summary-value">—</span>
                </div>
                <div class="summary-item">
                    <div class="summary-dot" style="background:#22c55e"></div>
                    <span class="summary-label">Concluídos</span>
                    <span class="summary-value">—</span>
                </div>
                <div class="summary-item">
                    <div class="summary-dot" style="background:#f59e0b"></div>
                    <span class="summary-label">Em andamento</span>
                    <span class="summary-value">—</span>
                </div>
                <div class="summary-item">
                    <div class="summary-dot" style="background:#ef4444"></div>
                    <span class="summary-label">Com alertas</span>
                    <span class="summary-value">—</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    /* ─── KPI Grid ───────────────────────────────────── */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .kpi-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: box-shadow var(--transition), transform var(--transition);
    }

    .kpi-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
    }

    .kpi-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .kpi-icon svg {
        width: 20px; height: 20px;
        stroke: currentColor; fill: none;
        stroke-width: 1.8;
        stroke-linecap: round; stroke-linejoin: round;
    }

    .kpi-icon--blue   { background: #eff6ff; color: #2563eb; }
    .kpi-icon--violet { background: #f5f3ff; color: #7c3aed; }
    .kpi-icon--amber  { background: #fffbeb; color: #d97706; }
    .kpi-icon--green  { background: #f0fdf4; color: #16a34a; }

    .kpi-body {
        display: flex;
        flex-direction: column;
        gap: .15rem;
    }

    .kpi-label {
        font-size: .75rem;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .kpi-value {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -.04em;
        color: var(--text);
    }

    /* ─── Dashboard rows ─────────────────────────────── */
    .dashboard-row {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .dashboard-row--secondary {
        grid-template-columns: 1fr 220px;
    }

    /* ─── Section headers ────────────────────────────── */
    .section-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: .95rem;
        font-weight: 700;
        letter-spacing: -.02em;
        color: var(--text);
    }

    .section-subtitle {
        font-size: .78rem;
        color: var(--text-muted);
        margin-top: .1rem;
    }

    /* ─── Period tabs ────────────────────────────────── */
    .period-tabs {
        display: flex;
        gap: .25rem;
        background: var(--bg);
        padding: 3px;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .period-tab {
        font-family: inherit;
        font-size: .75rem;
        font-weight: 600;
        padding: .25rem .6rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        background: none;
        color: var(--text-muted);
        transition: background var(--transition), color var(--transition);
    }

    .period-tab.active {
        background: var(--surface);
        color: var(--text);
        box-shadow: var(--shadow-sm);
    }

    /* ─── Chart placeholder ──────────────────────────── */
    .chart-placeholder {
        position: relative;
        height: 160px;
        border-radius: var(--radius-sm);
        overflow: hidden;
        background: var(--bg);
        border: 1px solid var(--border);
    }

    .chart-lines {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    .chart-empty-label {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .78rem;
        color: var(--text-light);
        font-weight: 500;
    }

    /* ─── Activity list ──────────────────────────────── */
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: .1rem;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .6rem 0;
        border-bottom: 1px solid var(--border);
    }

    .activity-item:last-child { border-bottom: none; }

    .activity-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--border);
        flex-shrink: 0;
        margin-top: .3rem;
    }

    .activity-info {
        display: flex;
        flex-direction: column;
        gap: .1rem;
    }

    .activity-title {
        font-size: .82rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    .activity-time {
        font-size: .72rem;
        color: var(--text-light);
    }

    /* ─── Table ──────────────────────────────────────── */
    .table-wrap {
        overflow-x: auto;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .82rem;
    }

    .data-table th {
        padding: .65rem .9rem;
        text-align: left;
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted);
        background: var(--bg);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .data-table td {
        padding: .75rem .9rem;
        border-bottom: 1px solid var(--border);
        color: var(--text);
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td { border-bottom: none; }

    .data-table tbody tr:hover td { background: var(--bg); }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .5rem;
        padding: 2rem;
        color: var(--text-light);
    }

    .empty-state svg {
        width: 32px; height: 32px;
        stroke: currentColor; fill: none;
        stroke-width: 1.5;
        stroke-linecap: round; stroke-linejoin: round;
        opacity: .5;
    }

    .empty-state span { font-size: .82rem; }

    /* ─── Summary ────────────────────────────────────── */
    .summary-list {
        display: flex;
        flex-direction: column;
        gap: .75rem;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .summary-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .summary-label {
        flex: 1;
        font-size: .82rem;
        color: var(--text-muted);
    }

    .summary-value {
        font-size: .85rem;
        font-weight: 600;
        color: var(--text);
    }

    /* ─── Ghost button ───────────────────────────────── */
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-family: inherit;
        font-size: .8rem;
        font-weight: 500;
        color: var(--text-muted);
        background: none;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .35rem .7rem;
        cursor: pointer;
        transition: background var(--transition), color var(--transition);
        white-space: nowrap;
    }

    .btn-ghost:hover {
        background: var(--bg);
        color: var(--text);
    }

    .btn-ghost svg {
        width: 13px; height: 13px;
        stroke: currentColor; fill: none;
        stroke-width: 2;
        stroke-linecap: round; stroke-linejoin: round;
    }

    /* ─── Responsivo ─────────────────────────────────── */
    @media (max-width: 1100px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .dashboard-row,
        .dashboard-row--secondary { grid-template-columns: 1fr; }
    }

    @media (max-width: 480px) {
        .kpi-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Period tabs
    document.querySelectorAll('.period-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.period-tab').forEach(function(t) {
                t.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
</script>
@endpush
