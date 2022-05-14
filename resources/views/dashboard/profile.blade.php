@extends('layouts.master', ['title' => 'Profile'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Profile</h2>
    </div>

    <div class="col-md-6">
        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ auth()->user()->name ?? old('name') }}">

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ auth()->user()->email ?? old('email') }}">

                @error('email')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">

                @error('password')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])">

                @error('image')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <img id="preview" alt="your image" width="100" src="{{ asset('storage/' . auth()->user()->image) ?? '' }}" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>
@stop