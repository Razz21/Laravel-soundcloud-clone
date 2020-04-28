@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{$file->title}}</div>

        <div class="card-body">

          <audio-player source='{{asset(Storage::url("files/{$file->id}/index.m3u8"))}}' :file="{{$file}}"></audio-player>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection



@section('scripts')
<script>


</script>
@endsection

@section('style')
@endsection
