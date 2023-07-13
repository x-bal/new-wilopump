@extends('layouts.master', ['title' => 'Edit Access'])

@section('content')
<div class="row">
    <div class="col-md-6">
        <h2 class="mb-3 lh-sm">Edit Access</h2>

        <form action="{{ route('access.store') }}" method="post" class="form-access">
            @csrf
            <div class="form-group mb-3">
                <label for="viewer">Viewer</label>
                <select name="viewer_id" id="viewer" class="form-control">
                    <option disabled selected>-- Select Viewer --</option>
                    @foreach($viewers as $viewer)
                    <option {{ $viewer->id == $user->id ? 'selected' : '' }} value="{{ $viewer->id }}">{{ $viewer->name }}</option>
                    @endforeach
                </select>

                @error('viewer_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="device">Device</label><br>
                <div class="row">
                    @foreach($devices as $device)
                    <div class="col-md-4">
                        <input class="form-check-input access-check" id="" name="device_id" type="checkbox" value="{{ $device->id }}" @if(in_array($device->id, $user->devices()->pluck('device_id')->toArray())) checked @endif>
                        <label class="form-check-label" for="">{{ $device->name }}</label>
                    </div>
                    @endforeach
                </div>

                @error('device_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror

                @foreach($user->devices as $dev)
                <input type="hidden" name="device_id[]" id="device-{{ $dev->id }}" value="{{ $dev->id }}">
                @endforeach
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
</div>
@stop

@push('script')
<script src="{{ asset('js/script.js') }}"></script>
@endpush