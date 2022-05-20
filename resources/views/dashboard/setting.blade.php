@extends('layouts.master', ['title' => 'Setting'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Setting</h2>
    </div>

    <div class="col-md-6">
        <form action="{{ route('setting.update', $apikey->id) }}" method="post">
            @csrf
            <div class="form-group">
                <input type="hidden" name="type" value="apikey">
                <label for="key">Google Api Key</label>
                <input type="text" name="key" id="key" class="form-control" value="{{ $apikey->key ?? old('key') }}">

                @error('key')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>

    <div class="col-md-6">
        <form action="{{ route('setting.update', $delay->id) }}" method="post">
            @csrf
            <div class="form-group">
                <input type="hidden" name="type" value="delay">
                <label for="key">Delay Time Slider</label>
                <input type="text" name="key" id="key" class="form-control" value="{{ $delay->key ?? old('key') }}">

                @error('key')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>
@stop