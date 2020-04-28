@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">{{$profile->name}}
                    <a href="{{ route('files.create')}}">{{__('Upload file')}}</a>
                </div>
                <div class="text-center">

                    <subscribe-button :profile="{{$profile}}" :init-subs="{{$profile->subscriptions}}">
                    </subscribe-button>

                </div>

                <div class="card-body">
                    <form id="profile-form" action="{{route('profiles.update', $profile->url)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group  mb-2 justify-content-center d-flex full-width">
                            <div class="profile-image">
                                <img src="{{asset($profile->thumbnail())}}" alt="{{$profile->name.' profile image'}}">
                                <div class="overlay" onclick="document.getElementById('image').click()">
                                    @error('image') <small class="text-danger">{{$message}}</small> @enderror
                                </div>
                            </div>
                            <input hidden type="file" accept="image/*" id="image" name="image" aria-describedby="imageHelp"
                                onchange="document.getElementById('profile-form').submit()">
                        </div>

                        <div class="form-group">

                            <input type="text" class="@error('name') is-invalid @enderror form-control" id="name" name="name"
                                aria-describedby="nameHelp" value="{{$profile->name}}">
                            <small id="nameHelp" class="form-text text-muted">{{__("Profile name")}}</small>
                            @error('name') <small class="text-danger">{{$message}}</small> @enderror
                        </div>

                        <div class="form-group">
                            <div class="d-flex align-items-baseline">

                                <span>{{env('APP_URL')}}//</span><input type="url" class="@error('url') is-invalid @enderror form-control" id="url"
                                    name="url" aria-describedby="urlHelp" value="{{$profile->url}}">
                            </div>

                            <small id="urlHelp" class="form-text text-muted">{{__("Profile url")}}</small>
                            @error('url') <small class="text-danger">{{$message}}</small> @enderror
                        </div>

                        <div class="form-group">
                            <textarea name="description" id="description" cols="30" rows="10"
                                class="@error('description') is-invalid @enderror form-control" aria-describedby="descriptionHelp">
              {{$profile->description}}
            </textarea>
                            <small id="descriptionHelp" class="form-text text-muted">{{__('Profile description')}}</small>
                            @error('description') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <button type="submit" class="btn btn-success mt-4">{{__('Update')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
