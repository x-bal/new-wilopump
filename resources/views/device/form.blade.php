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

            <div class="form-group row mb-3">
                <div class="col-md-6">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $device->name ?? old('name') }}">

                    @error('name')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="iddev">ID Device</label>
                    <input type="text" name="iddev" id="iddev" class="form-control" value="{{ $device->iddev ?? old('iddev') }}">

                    @error('iddev')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-6">
                    <label for="end_user">End User</label>
                    <input type="text" name="end_user" id="end_user" class="form-control" value="{{ $device->end_user ?? old('end_user') }}">

                    @error('end_user')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="lat">Latitude</label>
                    <input type="text" name="lat" id="lat" class="form-control" value="{{ $device->lat ?? old('lat') }}">

                    @error('lat')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>


            <div class="form-group mb-3 row">
                <div class="col-md-6">
                    <label for="type">Type</label>
                    <input type="text" name="type" id="type" class="form-control" value="{{ $device->type ?? old('type') }}">

                    @error('type')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="long">Longitude</label>
                    <input type="text" name="long" id="long" class="form-control" value="{{ $device->long ?? old('long') }}">

                    @error('long')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-6">
                    <label for="power">Power</label>
                    <input type="text" name="power" id="power" class="form-control" value="{{ $device->power ?? old('power') }}">

                    @error('type')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="modbus">Title Modbus</label>
                    <input type="text" name="modbus" id="modbus" class="form-control" value="{{ $device->modbus ?? old('modbus') }}" placeholder="Pump Status">

                    @error('modbus')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-6">
                    <label for="head">Head</label>
                    <input type="text" name="head" id="head" class="form-control" value="{{ $device->head ?? old('head') }}">

                    @error('head')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="digital">Title Digital Input</label>
                    <input type="text" name="digital" id="digital" class="form-control" value="{{ $device->digital ?? old('digital') }}" placeholder="Pump Status">

                    @error('digital')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-6">
                    <label for="flow">Flow</label>
                    <input type="text" name="flow" id="flow" class="form-control" value="{{ $device->flow ?? old('flow') }}">

                    @error('flow')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-control">

                    @error('image')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-3">
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </div>
        </form>
    </div>
</div>
@stop