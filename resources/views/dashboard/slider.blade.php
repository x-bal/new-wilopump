@extends('layouts.master', ['title' => 'Slider'])

@push('style')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
    button.gm-ui-hover-effect {
        visibility: hidden;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Slider</h2>

        <div id="carouselExampleControls" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div class="carousel-inner">
                        <div class=" carousel-item active" data-bs-interval="{{ $delay }}">
                            <div id="first-map" style="width: 100%; height: 480px;" class="d-block w-100"></div>
                        </div>
                        @foreach($devices as $device)
                        <div class="carousel-item" data-bs-interval="{{ $delay }}">
                            <div id="{{ $device->id }}" style="width: 100%; height: 480px;" class="d-block w-100 maps">{{ $device->lat }}, {{ $device->long }}, {{ $device->name }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev" style="margin-left: -80px;">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next" style="margin-right: -80px;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>
@stop

@push('script')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $apikey }}&callback=initMap" defer></script>

<script>
    let map, deviceMap;

    function initFirstMap() {
        let lat = parseFloat("{{ $first->lat }}");
        let long = parseFloat("{{ $first->long }}");

        let map = new google.maps.Map(document.getElementById("first-map"), {
            center: new google.maps.LatLng(lat, long),
            zoom: 12,
        });

        let marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: long
            },
            label: {
                text: "{{ $first->name }}",
                fontFamily: "Poppins",
                color: "#000",
                stroke: "#fff",
                fontSize: "14px",
            },
            map: map,
        });

        let rightMarker = new google.maps.Marker({
            position: {
                lat: lat + 0.0001,
                lng: long + 0.1
            },
            map: map,
        });

        let leftMarker = new google.maps.Marker({
            position: {
                lat: lat - 0.0001,
                lng: long - 0.1
            },
            map: map,
        });

        // marker.addListener("click", () => {
        let id = "{{ $first->id }}";

        $.ajax({
            url: '/api/get-device/' + id,
            type: 'GET',
            success: function(result) {
                let device = result.device;
                let modbus = result.modbus;
                let digital = result.digital;

                let infoFirst = `<div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Info Device</h5>
                                        <table>
                                            <tr>
                                                <th>Name</th>
                                                <td> : </td>
                                                <td>` + device.name + `</td>
                                            </tr>
                                            <tr>
                                                <th>Type</th>
                                                <td> : </td>
                                                <td>` + device.type + `</td>
                                            </tr>
                                            <tr>
                                                <th>Lat</th>
                                                <td> : </td>
                                                <td>` + device.lat + `</td>
                                            </tr>
                                            <tr>
                                                <th>Long</th>
                                                <td> : </td>
                                                <td>` + device.long + `</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>`;

                let mobdusFirst = `<div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Modbus</h5>
                                        <table>
                                            <tr>
                                                <th>Name</th>
                                                <td> : </td>
                                                <td>Val</td>
                                            </tr>`
                $.each(modbus, function(i, data) {
                    mobdusFirst += `<tr>
                                        <td>` + data.name + `</td>
                                        <td> : </td>
                                        <td>` + data.after + data.satuan + `</td>
                                    </tr>`
                })
                mobdusFirst += `</table>
                                    </div>
                                </div>`;

                let digitalFirst = `<div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Digital Input</h5>
                                        <table>
                                            <tr>
                                                <th>Name</th>
                                                <td> : </td>
                                                <td>Val</td>
                                            </tr>`
                $.each(digital, function(i, data) {
                    digitalFirst += `<tr>
                                        <td>` + data.name + `</td>
                                        <td> : </td>
                                        <td>` + data.val + `</td>
                                    </tr>`
                })
                digitalFirst += `</table>
                                    </div>
                                </div>`;



                let firstInfo = new google.maps.InfoWindow({
                    content: infoFirst,
                });

                let firstModbus = new google.maps.InfoWindow({
                    content: mobdusFirst,
                });

                let firstDigital = new google.maps.InfoWindow({
                    content: digitalFirst,
                });

                firstInfo.open({
                    anchor: marker,
                    map,
                    shouldFocus: true,
                });

                firstModbus.open({
                    anchor: leftMarker,
                    map,
                    shouldFocus: true,
                });

                firstDigital.open({
                    anchor: rightMarker,
                    map,
                    shouldFocus: true,
                });

                leftMarker.setVisible(false)
                rightMarker.setVisible(false)
            }
        })

        // });

        $('.maps').each(function(index, Element) {
            var coords = $(Element).text().split(",");

            if (coords.length != 3) {
                $(this).display = "none";
                return;
            }
            var latlng = new google.maps.LatLng(parseFloat(coords[0]), parseFloat(coords[1]));
            var myOptions = {
                zoom: 12,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: false,
                mapTypeControl: true,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                }
            };
            var devicemap = new google.maps.Map(Element, myOptions);

            var devmarker = new google.maps.Marker({
                position: latlng,
                label: {
                    text: coords[2],
                    fontFamily: "Poppins",
                    color: "#000",
                    stroke: "#fff",
                    fontSize: "14px",
                },
                map: devicemap
            });

            let rightDevMarker = new google.maps.Marker({
                position: {
                    lat: parseFloat(coords[0]) + 0.0001,
                    lng: parseFloat(coords[1]) + 0.1
                },
                map: devicemap,
            });

            let leftDevMarker = new google.maps.Marker({
                position: {
                    lat: parseFloat(coords[0]) - 0.0001,
                    lng: parseFloat(coords[1]) - 0.1
                },
                map: devicemap,
            });

            // devmarker.addListener("click", () => {
            let id = $(Element).attr('id');

            $.ajax({
                url: '/api/get-device/' + id,
                type: 'GET',
                success: function(result) {
                    let device = result.device;
                    let modbus = result.modbus;
                    let digital = result.digital;

                    let infodev = `<div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Info Device</h5>
                                        <table>
                                            <tr>
                                                <th>Name</th>
                                                <td> : </td>
                                                <td>` + device.name + `</td>
                                            </tr>
                                            <tr>
                                                <th>Type</th>
                                                <td> : </td>
                                                <td>` + device.type + `</td>
                                            </tr>
                                            <tr>
                                                <th>Lat</th>
                                                <td> : </td>
                                                <td>` + device.lat + `</td>
                                            </tr>
                                            <tr>
                                                <th>Long</th>
                                                <td> : </td>
                                                <td>` + device.long + `</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>`;

                    let modbusdev = `<div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Modbus</h5>
                                        <table>
                                            <tr>
                                                <th>Name</th>
                                                <td> : </td>
                                                <td>Val</td>
                                            </tr>`
                    $.each(modbus, function(i, data) {
                        modbusdev += `<tr>
                                        <td>` + data.name + `</td>
                                        <td> : </td>
                                        <td>` + data.after + data.satuan + `</td>
                                    </tr>`
                    })
                    modbusdev += `</table>
                                    </div>
                                </div>`;

                    let digitaldev = `<div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Digital Input</h5>
                                        <table>
                                            <tr>
                                                <th>Name</th>
                                                <td> : </td>
                                                <td>Val</td>
                                            </tr>`
                    $.each(digital, function(i, data) {
                        digitaldev += `<tr>
                                        <td>` + data.name + `</td>
                                        <td> : </td>
                                        <td>` + data.val + `</td>
                                    </tr>`
                    })
                    digitaldev += `</table>
                                    </div>
                                </div>`;



                    let devinfo = new google.maps.InfoWindow({
                        content: infodev,
                    });

                    let devmodbus = new google.maps.InfoWindow({
                        content: modbusdev,
                    });

                    let devdigi = new google.maps.InfoWindow({
                        content: digitaldev,
                    });

                    devinfo.open({
                        anchor: devmarker,
                        devicemap,
                        shouldFocus: true,
                    });

                    devmodbus.open({
                        anchor: leftDevMarker,
                        devicemap,
                        shouldFocus: true,
                    });

                    devdigi.open({
                        anchor: rightDevMarker,
                        devicemap,
                        shouldFocus: true,
                    });

                    leftDevMarker.setVisible(false)
                    rightDevMarker.setVisible(false)
                }
            })

            // });
        });
    }

    window.initMap = initFirstMap;
</script>
@endpush