@extends('layouts.app')

@section('title', __('auth.register.title'))

@push('styles')
  @vite('resources/css/register.css')
@endpush

@section('content')
  <div class="auth-page">
    <div class="container">
      <h1>{{ __('auth.register.create_account') }}</h1>
      <p>{{ __('auth.register.subtitle') }}</p>

      @if (session('status'))
        <div class="status">{{ session('status') }}</div>
      @endif

      @if ($errors->has('general'))
        <div class="alert">
          {{ $errors->first('general') }}
        </div>
      @endif

      <form method="POST" action="{{ route('register.attempt') }}">
        @csrf
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required autocomplete="given-name"
            autofocus class="{{ $errors->has('nombre') ? 'error' : '' }}">
          @if ($errors->has('nombre'))
            <div class="error-message">{{ $errors->first('nombre') }}</div>
          @endif
        </div>

        <div class="form-group">
          <label for="apellido">Apellido</label>
          <input id="apellido" type="text" name="apellido" value="{{ old('apellido') }}" required
            autocomplete="family-name" class="{{ $errors->has('apellido') ? 'error' : '' }}">
          @if ($errors->has('apellido'))
            <div class="error-message">{{ $errors->first('apellido') }}</div>
          @endif
        </div>

        <div class="form-group">
          <label for="email">{{ __('auth.register.email') }}</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
            class="{{ $errors->has('email') ? 'error' : '' }}">
          @if ($errors->has('email'))
            <div class="error-message">{{ $errors->first('email') }}</div>
          @endif
        </div>

        <div class="form-group">
          <label for="password">{{ __('auth.register.password') }}</label>
          <input id="password" type="password" name="password" required autocomplete="new-password"
            class="{{ $errors->has('password') ? 'error' : '' }}">
          <p>Must be at least 8 characters long</p>
          @if ($errors->has('password'))
            <div class="error-message">{{ $errors->first('password') }}</div>
          @endif
        </div>

        <div class="form-group">
          <label for="password_confirmation">{{ __('auth.register.password_confirmation') }}</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required
            autocomplete="new-password">
        </div>

        <button type="submit">{{ __('auth.register.submit') }}</button>
      </form>

      <div class="links">
        <p style="text-align: center; margin-top: 20px;">
          {{ __('auth.register.already_have_account') }}
          <a href="{{ route('login') }}"
            style="color: #1d4ed8; text-decoration: none;">{{ __('auth.register.login_link') }}</a>
        </p>
        <a class="back-link" href="{{ route('landing') }}">{{ __('auth.session_reset_success.back_to_home') }}</a>
      </div>
    </div>
  </div>
@endsection