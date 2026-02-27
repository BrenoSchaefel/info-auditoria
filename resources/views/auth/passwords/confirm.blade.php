@extends('layouts.auth')

@section('title', 'Confirmar senha')

@section('content')
<div class="auth-card">
    <h1 class="auth-card-title">Confirmar identidade</h1>
    <p class="auth-card-subtitle">Por segurança, confirme sua senha antes de continuar</p>

    <div class="info-box">
        Esta área contém informações sensíveis. Por favor, confirme sua senha para prosseguir.
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group" style="margin-bottom:1.4rem">
            <label for="password" class="form-label">Sua senha atual</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control has-toggle @error('password') is-invalid @enderror"
                    placeholder="••••••••"
                    required
                    autofocus
                    autocomplete="current-password"
                >
                <button type="button" class="input-toggle-password" tabindex="-1" aria-label="Mostrar senha">
                    <svg class="icon-eye" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="icon-eye-off" viewBox="0 0 24 24" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" data-loading>
            <span class="btn-label">Confirmar e continuar</span>
            <span class="btn-spinner"></span>
        </button>
    </form>

    @if (Route::has('password.request'))
        <p class="auth-footer-text">
            Esqueceu sua senha? <a href="{{ route('password.request') }}" class="auth-link">Recuperar acesso</a>
        </p>
    @endif
</div>
@endsection
