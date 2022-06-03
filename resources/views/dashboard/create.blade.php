@extends('layouts.master', ['title' => 'Create Access'])

@section('content')
<div class="row">
    <div class="col-md-6">
        <h2 class="mb-3 lh-sm">Create Access</h2>

        <form action="{{ route('access.store') }}" method="post" class="form-access">
            @csrf
            <div class="form-group mb-3">
                <label for="viewer">Viewer</label>
                <select name="viewer_id" id="viewer" class="form-control">
                    <option disabled selected>-- Select Viewer --</option>
                    @foreach($viewers as $viewer)
                    <option value="{{ $viewer->id }}">{{ $viewer->name }}</option>
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
                        <input class="form-check-input access-check" id="" type="checkbox" value="{{ $device->id }}">
                        <label class="form-check-label" for="">{{ $device->name }}</label>
                    </div>
                    @endforeach
                </div>

                @error('device_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror
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