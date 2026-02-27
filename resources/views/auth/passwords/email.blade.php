@extends('layouts.auth')

@section('title', 'Recuperar senha')

@section('content')
<div class="auth-card">
    <h1 class="auth-card-title">Recuperar senha</h1>
    <p class="auth-card-subtitle">Informe seu e-mail e enviaremos um link para redefinir sua senha</p>

    @if (session('status'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group" style="margin-bottom:1.4rem">
            <label for="email" class="form-label">E-mail cadastrado</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="seu@email.com"
                    required
                    autofocus
                    autocomplete="email"
                >
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" data-loading>
            <span class="btn-label">Enviar link de recuperação</span>
            <span class="btn-spinner"></span>
        </button>
    </form>

    <p class="auth-footer-text">
        Lembrou a senha? <a href="{{ route('login') }}" class="auth-link">Voltar ao login</a>
    </p>
</div>
@endsection
