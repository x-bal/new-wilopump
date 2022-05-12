@extends('layouts.master', ['title' => 'Detail Device'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Device - {{ $device->name }}</h4>
            </div>

            <div class="card-body">
                <div class="form-group row mb-3">
                    <div class="col-md-2">
                        <label for="name">Name</label>
                    </div>

                    <div class="col-md-10">
                        <input type="text" name="name" id="name" class="form-control" value="{{ $device->name ?? old('name') }}" disabled>

                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="form-group row mb-3">
                    <div class="col-md-2">
                        <label for="type">Type</label>
                    </div>

                    <div class="col-md-10">
                        <input type="text" name="type" id="type" class="form-control" value="{{ $device->type ?? old('type') }}" disabled>

                        @error('type')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-md-2">
                        <label for="lat">Latitude</label>
                    </div>
                    <div class="col-md-10">
                        <input type="number" name="lat" id="lat" class="form-control" value="{{ $device->lat ?? old('lat') }}" disabled>

                        @error('lat')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2">
                        <label for="long">Longitude</label>
                    </div>

                    <div class="col-md-10">
                        <input type="number" name="long" id="long" class="form-control" value="{{ $device->long ?? old('long') }}" disabled>

                        @error('long')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 my-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Modbus {{ $device->name }}</h4>
            </div>

            <div class="card-body">
                <form action="" method="post">
                    <div id="tableModbus" data-list='{"valueNames":["no","name","address","id","val","satuan","used"],"page":5,"pagination":true}'>

                        <div class="table-responsive scrollbar">
                            <table class="table table-bordered table-striped fs--1 mb-0">
                                <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="sort text-center" data-sort="no">No</th>
                                        <th class="sort" data-sort="name">Name</th>
                                        <th class="sort" data-sort="address">Address</th>
                                        <th class="sort" data-sort="id">Id Modbus</th>
                                        <th class="sort" data-sort="val">Val</th>
                                        <th class="sort" data-sort="satuan">Denomination</th>
                                        <th class="sort" data-sort="used">Used</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach($device->modbuses as $modbus)
                                    <tr>
                                        <td class="no text-center">{{ $loop->iteration }}</td>
                                        <td class="name">
                                            <input type="text" name="name" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-name" value="{{ $modbus->name }}">
                                        </td>
                                        <td class="address">
                                            <input type="text" name="address" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-address" value="{{ $modbus->address }}" disabled>
                                        </td>
                                        <td class="id">
                                            <input type="number" name="id" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-id" value="{{ $modbus->id_modbus }}" disabled>
                                        </td>
                                        <td class="val">
                                            <input type="text" name="val" id="val-{{ $modbus->id }}" class="form-control form-control-sm" value="{{ $modbus->val }}" disabled>
                                        </td>
                                        <td class="satuan">
                                            <input type="text" name="satuan" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-satuan" value="{{ $modbus->satuan }}">
                                        </td>
                                        <td class="used">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input modbus-used" data-id="{{ $modbus->id }}" type="checkbox" name="used" {{ $modbus->is_used == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="used">Used</label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                            <ul class="pagination mb-0"></ul>
                            <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12 my-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Digital Input {{ $device->name }}</h4>
            </div>

            <div class="card-body">
                <form action="" method="post">
                    <div id="tableDigital" data-list='{"valueNames":["no","digital","name","yes","no","used"],"page":5,"pagination":true}' class="table-list">

                        <div class="table-responsive scrollbar">
                            <table class="table table-bordered table-striped table-digital fs--1 mb-0">
                                <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="sort text-center" data-sort="no">No</th>
                                        <th class="sort" data-sort="digital">Digital Input</th>
                                        <th class="sort" data-sort="name">Name</th>
                                        <th class="sort" data-sort="yes">Alias (Yes)</th>
                                        <th class="sort" data-sort="no">Alias (No)</th>
                                        <th class="sort" data-sort="used">Used</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach($device->digitalInputs as $digital)
                                    <tr>
                                        <td class="no text-center">{{ $loop->iteration }}</td>
                                        <td class="digital text-center">
                                            <b>{{ $digital->digital_input }}</b>
                                        </td>
                                        <td class="name">
                                            <input type="text" name="name" data-id="{{ $digital->id }}" class="form-control form-control-sm digital-name" value="{{ $digital->name }}">
                                        </td>
                                        <td class="yes">
                                            <input type="text" name="yes" data-id="{{ $digital->id }}" class="form-control form-control-sm digital-yes" value="{{ $digital->yes }}">
                                        </td>
                                        <td class="no">
                                            <input type="text" name="no" data-id="{{ $digital->id }}" class="form-control form-control-sm digital-no" value="{{ $digital->no }}">
                                        </td>
                                        <td class="used">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input digital-used" data-id="{{ $digital->id }}" type="checkbox" name="used" {{ $digital->is_used == 1 ? 'checked' : '' }}>
                                                <label class=" form-check-label" for="used">Used</label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                            <ul class="pagination mb-0"></ul>
                            <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@push('script')
<script src="{{ asset('/js/script.js') }}"></script>
@endpush