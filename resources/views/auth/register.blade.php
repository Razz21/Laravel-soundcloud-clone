@extends('layouts.app')

@section('content')
<section class="hero is-primary">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">
        {{ __('Register') }}
      </h1>
    </div>
  </div>
</section>

<div class="columns is-marginless is-centered">
  <div class="column is-5">
    <div class="card">
      <header class="card-header">
        <p class="card-header-title">{{ __('Register') }}</p>
      </header>

      <div class="card-content">
        <form class="register-form" method="POST" action="{{ route('register') }}">

          @csrf

          <div class="field is-horizontal">
            <div class="field-label">
              <label class="label">{{ __('Name') }}</label>
            </div>

            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input class="input" id="name" type="name" name="name" value="{{ old('name') }}" required autofocus>
                </p>

                @error('name')
                <p class="help is-danger">
                  {{ $message }}
                </p>
                @enderror
              </div>
            </div>
          </div>

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
            <div class="field-label">
              <label class="label">{{ __('Confirm Password') }}</label>
            </div>

            <div class="field-body">
              <div class="field">
                <p class="control">
                  <input class="input" id="password-confirm" type="password" name="password_confirmation" required>
                </p>
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label"></div>

            <div class="field-body">
              <div class="field is-grouped">
                <div class="control">
                  <button type="submit" class="button is-primary">{{ __('Register') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
