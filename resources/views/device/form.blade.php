@extends('layouts.master', ['title' => $act == 'create' ? 'Create Device' : 'Edit Device'])

@section('content')
<div class="row">
    <div class="col-md-12">
        @if($act == 'create')
        <h2 class="mb-3 lh-sm">Create Device</h2>
        @else
        <h2 class="mb-3 lh-sm">Edit Device</h2>
        @endif

        <form action="{{ $action }}" method="post" enctype="multipart/form-data">
            @if($act == 'edit')
            @method('PATCH')
            @endif
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $device->name ?? old('name') }}">

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="satuan">Satuan</label>
                <input type="text" name="satuan" id="satuan" class="form-control" value="{{ $device->satuan ?? old('satuan') }}">

                @error('satuan')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" name="type" id="type" class="form-control" value="{{ $device->type ?? old('type') }}">

                @error('type')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="lat">Latitude</label>
                <input type="number" name="lat" id="lat" class="form-control" value="{{ $device->lat ?? old('lat') }}">

                @error('lat')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="long">Longitude</label>
                <input type="number" name="long" id="long" class="form-control" value="{{ $device->long ?? old('long') }}">

                @error('long')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </div>
        </form>
    </div>
</div>
@stop