@extends('layouts.app')

@section('content')
<div class="container">
  <div class="columns is-marginless is-centered">
    <div class="column is-7">
      <nav class="card">
        <header class="card-header">
          <p class="card-header-title">
            Dashboard
          </p>
        </header>

        <div class="card-content">

          {{__('You are logged in!')}}
        </div>
    </div>
  </div>
</div>
</div>
@endsection

@section('scripts')

<script>
  // console.dir(window.Echo)
  // window.Echo.channel('home').listen('.example.event', e=>console.log(e)); //dot before event name!
</script>
@endsection
