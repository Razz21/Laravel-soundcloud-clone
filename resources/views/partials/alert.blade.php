<div class="flash-message">
  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
  @if(Session::has($msg))
  <div class="notification  is-{{ $msg }}">
    <button class="delete"></button>
    {{ Session::get($msg) }}
  </div>
  @endif
  @endforeach
</div>
