@extends('layouts.master', ['title' => 'Detail Device'])
@push('style')
<style>
    button.gm-ui-hover-effect {
        visibility: hidden;
    }

    .map-label {
        -webkit-text-stroke: 1px rgba(255, 255, 255, .4) !important;
        color: #DB0202 !important;
        top: 35px;
        left: 0;
        position: relative;
        font-weight: bold;
        font-size: 16px !important;
    }

    .gm-style .gm-style-iw {
        background-color: #009c81;
    }
</style>
@endpush
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
                <div id="tableModbus" data-list='{"valueNames":["no","check","name","address","id","val","after","math","satuan","used"],"page":5,"pagination":true}'>

                    <div class="table-responsive scrollbar">
                        <table class="table table-bordered table-striped fs--1 mb-0">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th class="sort text-center" data-sort="check">#</th>
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
                                    <td class="check text-center">
                                        <input type="checkbox" name="check" data-id="{{ $modbus->id }}" class="modbus-merge">
                                    </td>
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
                                        <select name="mark" class="form-control form-control-sm modbus-mark mark-{{ $modbus->id }}" data-id="{{ $modbus->id }}">
                                            <option {{ $math[0] == 'x' ? 'selected' : '' }} value="x">x</option>
                                            <option {{ $math[0] == ':' ? 'selected' : '' }} value=":">:</option>
                                            <option {{ $math[0] == '+' ? 'selected' : '' }} value="+">+</option>
                                            <option {{ $math[0] == '-' ? 'selected' : '' }} value="-">-</option>
                                            <option {{ $math[0] == '&' ? 'selected' : '' }} value="&">PV[Units]</option>
                                        </select>
                                        <br>
                                        <input type="number" name="math" id="math-{{ $modbus->id }}" data-id="{{ $modbus->id }}" class="form-control form-control-sm modbus-math" value="{{ $math[1] ?? 1 }}">
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
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Merge
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-12 my-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Merge Modbus {{ $device->name }}</h4>
            </div>

            <div class="card-body">
                <div id="tableModbus" data-list='{"valueNames":["no","check","name","address","id","val","after","math","satuan","used"],"page":5,"pagination":true}'>

                    <div class="table-responsive scrollbar">
                        <table class="table table-bordered table-striped fs--1 mb-0">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th class="sort text-center" data-sort="no">No</th>
                                    <th class="sort" data-sort="name">Name</th>
                                    <th class="sort" data-sort="address">Modbus</th>
                                    <th class="sort" data-sort="val">Val</th>
                                    <th class="sort" data-sort="math" colspan="2">Math</th>
                                    <th class="sort" data-sort="after">Val(After)</th>
                                    <th class="sort" data-sort="satuan">Unit</th>
                                    <th class="sort" data-sort="used">Used</th>
                                    <th class="sort" data-sort="check">Act</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($device->merges as $merge)
                                <tr>
                                    <td class="no text-center">{{ $loop->iteration }}</td>
                                    <td class="name">
                                        <input type="text" name="name" data-id="{{ $merge->id }}" class="form-control form-control-sm modbus-name" value="{{ $merge->name }}">
                                    </td>
                                    <td class="address text-center">
                                        @foreach($merge->modbuses as $mod)
                                        ({{ $mod->id }}) {{ $mod->address }} <br>
                                        @endforeach
                                    </td>
                                    <td class="val">
                                        <b>{{ $merge->val }}</b>
                                    </td>
                                    <td class="math" colspan="2">
                                        @php
                                        $math = explode(',', $merge->math)
                                        @endphp
                                        <select name="mark" class="form-control form-control-sm mark-{{ $merge->id }}">
                                            <option {{ $math[0] == 'x' ? 'selected' : '' }} value="x">x</option>
                                            <option {{ $math[0] == ':' ? 'selected' : '' }} value=":">:</option>
                                            <option {{ $math[0] == '+' ? 'selected' : '' }} value="+">+</option>
                                            <option {{ $math[0] == '-' ? 'selected' : '' }} value="-">-</option>
                                        </select>
                                        <br>
                                        <input type="number" name="math" data-id="{{ $merge->id }}" class="form-control form-control-sm modbus-math" value="{{ $math[1] ?? 1 }}">
                                    </td>
                                    <td class="after">
                                        <input type="text" name="after" id="after-{{ $merge->id }}" class="form-control form-control-sm" value="{{ $merge->after }}" disabled>
                                    </td>
                                    <td class="satuan">
                                        <input type="text" name="satuan" data-id="{{ $merge->id }}" class="form-control form-control-sm modbus-satuan" value="{{ $merge->satuan }}">
                                    </td>
                                    <td class="used">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input modbus-used" data-id="{{ $merge->id }}" type="checkbox" name="used" {{ $merge->is_used == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="used">Used</label>
                                        </div>
                                    </td>
                                    <td class="check">
                                        <form action="{{ route('merge.delete', $merge->id) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this merge ?')"><i class="fas fa-trash"></i></button>
                                        </form>
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


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Merge Modbus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('modbus.merge') }}" method="post" class="form-merge">
                    @csrf
                    <div class="form-group">
                        <label for="name">Merge Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">

                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="convert">Convert To</label>
                        <select name="convert" id="convert" class="form-control">
                            <option disabled selected>-- Select Convert --</option>
                            <option value="be">Big Endian</option>
                            <option value="le">Little Endian</option>
                            <option value="mbe">Mid Big Endian</option>
                            <option value="mle">Mid Little Endian</option>
                        </select>

                        @error('convert')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
            </form>
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

        let infoMarker = new google.maps.Marker({
            position: {
                lat: lat + 0.026,
                lng: long - 0.12
            },
            map: map,
        });

        let imgMarker = new google.maps.Marker({
            position: {
                lat: lat - 0.010,
                lng: long - 0.12
            },
            map: map,
        });

        let upMarker = new google.maps.Marker({
            position: {
                lat: lat - 0.050,
                lng: long + 0.1
            },
            map: map,
        });

        let downMarker = new google.maps.Marker({
            position: {
                lat: lat - 0.070,
                lng: long - 0.12
            },
            map: map,
        });


        let id = "{{ $device->id }}";

        $.ajax({
            url: '/api/get-device/' + id,
            type: 'GET',
            success: function(result) {
                let dataMarker = [marker, infoMarker, imgMarker, upMarker, downMarker];
                getData(result.device, result.image, result.modbus, result.digital, result.history, map, dataMarker)
            }
        })

        function getData(device, image, modbus, digital, history, dataMap, dataMarker) {
            let infoFirst = `<div class="card" style="background-color: #009c81;">
                                <h6>` + device.name + `</h6>
                                <table>
                                    <tr>
                                        <td>Type</td>
                                        <td> : </td>
                                        <td>` + device.type + `</td>
                                    </tr>
                                    <tr>
                                        <td>Power</td>
                                        <td> : </td>
                                        <td>` + device.power + `</td>
                                    </tr>
                                    <tr>
                                        <td>Head</td>
                                        <td> : </td>
                                        <td>` + device.head + `</td>
                                    </tr>
                                    <tr>
                                        <td>Flow</td>
                                        <td> : </td>
                                        <td>` + device.flow + `</td>
                                    </tr>
                                    <tr>
                                        <td>Last Data Send</td>
                                        <td> : </td>
                                        <td>` + history + `</td>
                                    </tr>
                                </table>
                            </div>`;
            dataMarker[1].setMap(null)

            let firstInfo = new google.maps.InfoWindow({
                content: infoFirst,
            });

            firstInfo.open({
                anchor: dataMarker[1],
                map: dataMap,
                shouldFocus: true,
            });

            let imgFirst = `<div class="card text-center" style="background-color: #009c81;">
                                <img src="` + image + `" alt="" width="60px">
                            </div>`;

            dataMarker[2].setMap(null)
            let firstImg = new google.maps.InfoWindow({
                content: imgFirst,
            });

            firstImg.open({
                anchor: dataMarker[2],
                map: dataMap,
                shouldFocus: true,
            });

            let upFirst = ``;

            if (modbus.length > 0) {
                upFirst = `<div class="card" style="background-color: #009c81;">
                                        <h6>` + device.modbus + `</h6>
                                        <table>`
                $.each(modbus, function(i, data) {
                    upFirst += `<tr>
                                        <td>` + data.name + `</td>
                                        <td> : </td>`;
                    if (data.after == null) {
                        upFirst += `<td>` + data.val + data.satuan + `</td>`;
                    } else {
                        upFirst += `<td>` + data.val + data.satuan + `</td>`;
                    }
                })
                upFirst += `        </tr>
                                </table>
                            </div>`;

                dataMarker[3].setMap(null)

                let upWindow = new google.maps.InfoWindow({
                    content: imgFirst,
                });
                upWindow.setContent(upFirst)

                upWindow.open({
                    anchor: dataMarker[3],
                    map: dataMap,
                    shouldFocus: true,
                });
            } else {
                upFirst = ``;
            }

            let downFirst = ``;

            if (digital.length > 0) {
                downFirst = `<div class="card" style="background-color: #009c81;">
                                <h6>` + device.digital + `</h6>
                                    <table>`
                $.each(digital, function(i, data) {
                    downFirst += `<tr>
                                    <td>` + data.name + `</td>
                                    <td> : </td>`;
                    if (data.val == 1) {
                        downFirst += `<td>` + data.yes + `</td>`
                    } else {
                        downFirst += `<td>` + data.no + `</td>`
                    }
                    downFirst += `</tr>`
                })
                downFirst += `</table>
                            </div>`;

                dataMarker[4].setMap(null)

                let downWindow = new google.maps.InfoWindow({
                    content: downFirst,
                });


                downWindow.open({
                    anchor: dataMarker[4],
                    map: dataMap,
                    shouldFocus: true,
                });

            } else {
                downFirst = ``;
            }
        }

        infoMarker.setVisible(false)
        imgMarker.setVisible(false)
        upMarker.setVisible(false)
        downMarker.setVisible(false)
    }

    window.initMap = initMap;
</script>
@endpush