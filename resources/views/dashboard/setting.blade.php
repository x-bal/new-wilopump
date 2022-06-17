@extends('layouts.master', ['title' => 'Setting'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Setting</h2>
    </div>

    <div class="col-md-6 mb-3">
        <form action="{{ route('setting.update', $apikey->id) }}" method="post" class="form-apikey">
            @csrf
            <div class="form-group">
                <input type="hidden" name="type" value="apikey">
                <label for="apikey">Google Api Key</label>
                <input type="text" name="key" id="apikey" class="form-control" value="{{ $apikey->key ?? old('key') }}">

                @error('key')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </form>
    </div>

    <div class="col-md-6 mb-3">
        <form action="{{ route('setting.update', $delay->id) }}" method="post" class="form-slider">
            @csrf
            <div class="form-group">
                <input type="hidden" name="type" value="delay">
                <label for="slider">Delay Time Slider (s)</label>
                <input type="text" name="key" id="slider" class="form-control" value="{{ $delay->key ?? old('key') }}">

                @error('key')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </form>
    </div>

    <div class="col-md-6">
        <form action="{{ route('setting.update', $secretKey->id) }}" method="post" class="form-secret">
            @csrf
            <div class="form-group">
                <input type="hidden" name="type" value="secret">
                <label for="secret">Secret Key</label>
                <input type="text" name="key" id="secret" class="form-control" value="{{ $secretKey->key ?? old('key') }}">

                @error('key')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </form>
    </div>
</div>
@stop

<script>
    $("#apikey").on("change", function() {
        $("#form-apikey").submit()
    });

    $("#slider").on("change", function() {
        $("#form-slider").submit()
    });

    $("#secret").on("change", function() {
        $("#form-secret").submit()
    });
</script>