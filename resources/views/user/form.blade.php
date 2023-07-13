@extends('layouts.master', ['title' => $act == 'create' ? 'Create User' : 'Edit User'])

@section('content')
<div class="row">
    <div class="col-md-12">
        @if($act == 'create')
        <h2 class="mb-3 lh-sm">Create User</h2>
        @else
        <h2 class="mb-3 lh-sm">Edit User</h2>
        @endif

        <form action="{{ $action }}" method="post" enctype="multipart/form-data">
            @if($act == 'edit')
            @method('PATCH')
            @endif
            @csrf

            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name ?? old('name') }}">

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email ?? old('email') }}">

                @error('email')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" value="{{ old('password') }}">

                @error('password')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="level">Level</label>
                <select name="level" id="level" class="form-control">
                    <option disabled selected>-- Select Level --</option>
                    <option {{ $user->level == 'Admin' ? 'selected' : '' }} value="Admin">Admin</option>
                    <option {{ $user->level == 'Viewer' ? 'selected' : '' }} value="Viewer">Viewer</option>
                </select>

                @error('level')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control" value="{{ $user->image ?? old('image') }}">

                @error('image')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </div>
        </form>
    </div>
</div>
@stop