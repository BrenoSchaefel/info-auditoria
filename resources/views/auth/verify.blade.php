@extends('layouts.auth')

@section('title', 'Verificar e-mail')

@section('content')
<div class="auth-card" style="text-align:center">

    <div style="display:flex;justify-content:center;margin-bottom:1.5rem">
        <div style="
            width:72px;height:72px;
            background:linear-gradient(135deg,#eff6ff 0%,#e0e7ff 100%);
            border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            border:2px solid #bfdbfe;
        ">
            <svg viewBox="0 0 24 24" style="width:32px;height:32px;stroke:#2563eb;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>
    </div>

    <h1 class="auth-card-title" style="text-align:center">Verifique seu e-mail</h1>
    <p class="auth-card-subtitle" style="text-align:center;margin-bottom:1.5rem">
        Enviamos um link de verificação para o seu endereço de e-mail. Por favor, acesse sua caixa de entrada e clique no link para ativar sua conta.
    </p>

    @if (session('resent'))
        <div class="alert alert-success" style="text-align:left">
            <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span>Um novo link de verificação foi enviado para o seu e-mail.</span>
        </div>
    @endif

    <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:1.25rem">
        Não recebeu o e-mail? Verifique sua pasta de spam ou solicite um novo link abaixo.
    </p>

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-primary" data-loading>
            <span class="btn-label">Reenviar e-mail de verificação</span>
            <span class="btn-spinner"></span>
        </button>
    </form>

    <p class="auth-footer-text">
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;cursor:pointer;font-family:inherit" class="auth-link">
                Sair da conta
            </button>
        </form>
    </p>
</div>
@endsection
