@extends('layouts.master', ['title' => 'Detail Device'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Detail Device - {{ $device->name }}</h2>

        <div id="map" style="width: 100%; height: 480px;"></div>
    </div>

    <div class="col-md-12 my-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Modbus {{ $device->name }}</h4>
            </div>

            <div class="card-body">
                <div id="tableModbus" data-list='{"valueNames":["no","name","address","id","val","after","math","satuan","used"],"page":5,"pagination":true}'>

                    <div class="table-responsive scrollbar">
                        <table class="table table-bordered table-striped fs--1 mb-0">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th class="sort text-center" data-sort="no">No</th>
                                    <th class="sort" data-sort="name">Name</th>
                                    <th class="sort" data-sort="address">Address</th>
                                    <th class="sort" data-sort="id">Id Modbus</th>
                                    <th class="sort" data-sort="val">Val</th>
                                    <th class="sort" data-sort="math" colspan="2">Math</th>
                                    <th class="sort" data-sort="after">Val(After)</th>
                                    <th class="sort" data-sort="satuan">Unit</th>
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
                                    <td class="math" colspan="2">
                                        @php
                                        $math = explode(',', $modbus->math)
                                        @endphp
                                        <select name="mark" class="form-control form-control-sm mark-{{ $modbus->id }}">
                                            <option {{ $math[0] == 'x' ? 'selected' : '' }} value="x">x</option>
                                            <option {{ $math[0] == ':' ? 'selected' : '' }} value=":">:</option>
                                            <option {{ $math[0] == '+' ? 'selected' : '' }} value="+">+</option>
                                            <option {{ $math[0] == '-' ? 'selected' : '' }} value="-">-</option>
                                        </select>
                                        <br>
                                        <input type="number" name="math" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-math" value="{{ $math[1] ?? '' }}">
                                    </td>
                                    <td class="after">
                                        <input type="text" name="after" id="after-{{ $modbus->id }}" class="form-control form-control-sm" value="{{ $modbus->after }}" disabled>
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
                    <div id="tableDigital" data-list='{"valueNames":["no","digital","name","yes","no","val","used"],"page":5,"pagination":true}' class="table-list">

                        <div class="table-responsive scrollbar">
                            <table class="table table-bordered table-striped table-digital fs--1 mb-0">
                                <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="sort" data-sort="digital">Digital Input</th>
                                        <th class="sort" data-sort="name">Name</th>
                                        <th class="sort" data-sort="yes">Alias (Yes)</th>
                                        <th class="sort" data-sort="no">Alias (No)</th>
                                        <th class="sort" data-sort="val">Value</th>
                                        <th class="sort" data-sort="used">Used</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach($device->digitalInputs as $digital)
                                    <tr>
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
                                        <td class="val">
                                            <input type="text" name="val" data-id="{{ $digital->id }}" class="form-control form-control-sm digital-val" value="{{ $digital->val }} ({{ $digital->val == 1 ? $digital->yes : $digital->no }})" disabled>
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
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $apikey }}&callback=initMap" defer></script>

<script>
    function initMap() {
        let lat = parseFloat("{{ $device->lat }}");
        let long = parseFloat("{{ $device->long }}");

        const map = new google.maps.Map(document.getElementById("map"), {
            center: new google.maps.LatLng(lat, long),
            zoom: 12,
        });

        const marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: long
            },
            map,
        });

        marker.addListener("click", () => {
            let id = "{{ $device->id }}";

            $.ajax({
                url: '/api/get-device/' + id,
                type: 'GET',
                success: function(result) {
                    let device = result.device;

                    let contentString = '<div id="content"><div id="siteNotice"></div><h5 id="firstHeading" class="firstHeading">' + device.name + '</h5><div id="bodyContent"><p><b>Name : </b>' + device.name + '<br><b>Type : </b>' + device.type + '<br><b>Lat : </b>' + device.lat + '<br><b>Long : </b>' + device.long + '<br></p></div></div>';

                    const infowindow = new google.maps.InfoWindow({
                        content: contentString,
                    });

                    infowindow.open({
                        anchor: marker,
                        map,
                        shouldFocus: true,
                    });
                }
            })

        });
    }

    window.initMap = initMap;
</script>
@endpush