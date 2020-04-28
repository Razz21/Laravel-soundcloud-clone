@extends('layouts.app')

@section('content')

<section class="hero is-primary">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">
        {{ __('Confirm Password') }}
      </h1>
    </div>
  </div>
</section>


<div class="columns is-marginless is-centered">
  <div class="column is-5">
    <div class="card">

      <header class="card-header">
        <p class="card-header-title">{{ __('Confirm Password') }}</p>
      </header>
      {{ __('Please confirm your password before continuing.') }}

      <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">{{ __('Password') }}</label>
          </div>

          <div class="field-body">
            <div class="field">
              <p class="control">
                <input class="input" id="password" type="password" name="password" value="{{ old('password') }}" required autofocus>
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
            <div class="field is-grouped">
              <div class="control">
                <button type="submit" class="button is-primary">{{ __('Confirm Password') }}
                </button>
                @if (Route::has('password.request'))
                <div class="control">

                  <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                  </a>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection
