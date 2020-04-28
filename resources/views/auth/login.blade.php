@extends('layouts.app')

@section('content')

<section class="hero is-primary">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">
        {{ __('Login') }}
      </h1>
    </div>
  </div>
</section>

<div class="columns is-marginless is-centered">
  <div class="column is-5">
    <div class="card">
      <header class="card-header">
        <p class="card-header-title">{{ __('Login') }}</p>
      </header>

      <div class="card-content">
        <form class="login-form" method="POST" action="{{ route('login') }}">
          @csrf

          <div class="field is-horizontal">
            <div class="field-label">
              <label class="label">{{ __('E-Mail Address') }}</label>
            </div>

            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </p>

                @error('email')
                <p class="help is-danger">
                  {{ $message }}
                </p>
                @enderror
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label">
              <label class="label">{{ __('Password') }}</label>
            </div>

            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input class="input" id="password" type="password" name="password" required>
                </p>

                @error('password')
                <p class="help is-danger">
                  {{ $message }}
                </p>
                @enderror
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label"></div>

            <div class="field-body">
              <div class="field">
                <p class="control">
                  <label class="checkbox">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />{{ __('Remember Me') }}
                  </label>
                </p>
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label"></div>

            <div class="field-body">
              <div class="field is-grouped">
                <div class="control">
                  <button type="submit" class="button is-primary"> {{ __('Login') }}</button>
                </div>
                @if (Route::has('password.request'))
                <div class="control">
                  <a href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                  </a>
                </div>
                @endif
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
