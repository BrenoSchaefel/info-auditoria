@extends('layouts.auth')

@section('title', 'Criar conta')

@section('content')
<div class="auth-card">
    <h1 class="auth-card-title">Criar conta</h1>
    <p class="auth-card-subtitle">Preencha os dados abaixo para se registrar</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Nome completo</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
                <input
                    id="name"
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="Seu nome"
                    required
                    autofocus
                    autocomplete="name"
                >
            </div>
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">E-mail</label>
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
                    autocomplete="email"
                >
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Senha</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </span>
                <input
                    id="password"
                    id="password-strength-input"
                    type="password"
                    name="password"
                    class="form-control has-toggle @error('password') is-invalid @enderror"
                    placeholder="Mínimo 8 caracteres"
                    required
                    autocomplete="new-password"
                >
                <button type="button" class="input-toggle-password" tabindex="-1" aria-label="Mostrar senha">
                    <svg class="icon-eye" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="icon-eye-off" viewBox="0 0 24 24" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            <div class="password-strength">
                <div class="password-strength-bar">
                    <div class="strength-segment"></div>
                    <div class="strength-segment"></div>
                    <div class="strength-segment"></div>
                    <div class="strength-segment"></div>
                    <div class="strength-segment"></div>
                </div>
                <span class="password-strength-label"></span>
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="margin-bottom:1.4rem">
            <label for="password-confirm" class="form-label">Confirmar senha</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </span>
                <input
                    id="password-confirm"
                    type="password"
                    name="password_confirmation"
                    class="form-control has-toggle"
                    placeholder="Repita a senha"
                    required
                    autocomplete="new-password"
                >
                <button type="button" class="input-toggle-password" tabindex="-1" aria-label="Mostrar senha">
                    <svg class="icon-eye" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="icon-eye-off" viewBox="0 0 24 24" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" data-loading>
            <span class="btn-label">Criar conta</span>
            <span class="btn-spinner"></span>
        </button>
    </form>

    <p class="auth-footer-text">
        Já tem uma conta? <a href="{{ route('login') }}" class="auth-link">Entrar</a>
    </p>
</div>

@push('scripts')
<script>
    // Vincula o indicador de força ao campo correto nesta página
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('password');
        if (input) input.id = 'password-strength-input';
    });
</script>
@endpush
@endsection
