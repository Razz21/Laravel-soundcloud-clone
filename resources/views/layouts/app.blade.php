<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>


  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
  <script src="https://kit.fontawesome.com/f0d8d9037e.js" crossorigin="anonymous"></script>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @yield('style')
</head>

<body>
  <div id="app">
    <nav class="navbar" role="navigation" aria-label="main navigation">


      <div class="navbar-brand">
        <a class="navbar-item" href="{{ url('/') }}">
          {{ config('app.name', 'Laravel') }}
        </a>

        <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navMenu">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>



      <div class="navbar-menu" id="navMenu">
        <div class="navbar-start"></div>

        <div class="navbar-end">
          @guest
          <a class="navbar-item " href="{{ route('login') }}">{{ __('Login') }}</a>
          <a class="navbar-item " href="{{ route('register') }}">{{ __('Register') }}</a>
          @else
          <div class="navbar-item has-dropdown is-hoverable">

            <a class="navbar-link" href="{{ auth()->user()->profile->path() }}">{{ Auth::user()->name }}</a>

            <div class="navbar-dropdown">

              <a class="navbar-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>

            </div>
          </div>
          @endguest
        </div>
      </div>
    </nav>

    <main class="container-fluid relative">
      @include('partials.alert')
      @yield('content')
    </main>
  </div>


  <!-- Scripts -->


  <script>
    window.AuthUser = '{!! auth()->user() !!}'
    window.__auth = function(){
      try {
        return JSON.parse(AuthUser)
      } catch(error){
        return null
      }
    }
    // prevent drop files outside dedicated components
    window.addEventListener('drop', e=>e.preventDefault(), false)
    window.addEventListener('dragover', e=>e.preventDefault(), false)
    document.addEventListener('DOMContentLoaded', () => {

        // Get all "navbar-burger" elements
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

        // Check if there are any navbar burgers
        if ($navbarBurgers.length > 0) {

          // Add a click event on each of them
          $navbarBurgers.forEach( el => {
            el.addEventListener('click', () => {

              // Get the target from the "data-target" attribute
              const target = el.dataset.target;
              const $target = document.getElementById(target);

              // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
              el.classList.toggle('is-active');
              $target.classList.toggle('is-active');

            });
          });
        }
    });
  </script>

  {{-- hot reload --}}
  <script src="{{ (env('APP_ENV') === 'local') ? mix('js/app.js') : asset('js/app.js') }}"></script>
  <echo-connection></echo-connection>

  @yield('scripts')
</body>

</html>
