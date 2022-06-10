@extends('layouts.master', ['title' => 'Slider'])

@push('style')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

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

    /* .gm-style .gm-style-iw {
        
    } */
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(request()->is('slider') && request('q') == 'full')
        <h2 class="mb-3 lh-sm">Slider</h2>
        @else
        <h2 class="mb-3 lh-sm">Slider</h2>

        <a href="/slider?q=full" target="__blank" class="btn btn-success mb-3">Full Size</a>
        @endif
        <div id="carouselExampleControls" class="carousel carousel-dark slide mb-3" data-bs-ride="carousel">
            <div class="row justify-content-center">
                <div class="col-md-12" style="width: 96%;">
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

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev" style="margin-left: -10%">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next" style="margin-right: -10%">
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
    let map;

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
            map: map,
            label: {
                text: "{{ $first->name }}",
                color: "#d93025",
                fontFamily: "Poppins",
                className: "map-label"
            }
        });

        let infoMarker = new google.maps.Marker({
            position: {
                lat: lat + 0.001,
                lng: long - 0.12
            },
            map: map,
        });

        let imgMarker = new google.maps.Marker({
            position: {
                lat: lat - 0.060,
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
                lng: long - 0.001
            },
            map: map,
        });


        let id = "{{ $first->id }}";

        setInterval(function() {
            $.ajax({
                url: '/api/get-device/' + id,
                type: 'GET',
                success: function(result) {
                    let dataMarker = [marker, infoMarker, imgMarker, upMarker, downMarker];
                    getData(result.device, result.image, result.modbus, result.digital, result.history, map, dataMarker, result.merge)
                }
            })
        }, 3000)

        function getData(device, image, modbus, digital, history, dataMap, dataMarker, merge) {
            let infoFirst = `<div class="card" style="">
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
                                    <tr>
                                        <td colspan="3" class="text-center"><a href="/device/` + device.id + `">More Detail</a></td>
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

            let imgFirst = `<div class="card text-center" style="">
                                <img src="` + image + `" alt="" width="100px">
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
                upFirst = `<div class="card" style="">
                                        <h6>` + device.modbus + `</h6>
                                        <table>`
                $.each(modbus, function(i, data) {
                    if (data.merge_id == 0) {
                        upFirst += `<tr>
                                        <td>` + data.name + `</td>
                                        <td> : </td>`;
                        if (data.after == null) {
                            upFirst += `<td>` + data.val + data.satuan + `</td>`;
                        } else {
                            upFirst += `<td>` + data.val + data.satuan + `</td>`;
                        }
                        upFirst += `</tr>`
                    }
                })

                if (merge.length > 0) {
                    $.each(merge, function(i, mrg) {
                        upFirst += `<tr>
                                        <td>` + mrg.name + `</td>
                                        <td> : </td>`;
                        if (mrg.after == null) {
                            upFirst += `<td>` + mrg.val + mrg.unit + `</td>`;
                        } else {
                            upFirst += `<td>` + mrg.val + mrg.unit + `</td>`;
                        }
                    });
                }

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
                downFirst = `<div class="card" style="">
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



        $('.maps').each(function(index, Element) {
            var coords = $(Element).text().split(",");

            if (coords.length != 3) {
                $(this).display = "none";
                return;
            }

            var devLat = parseFloat(coords[0]);
            var devLong = parseFloat(coords[1]);

            var latlng = new google.maps.LatLng(parseFloat(coords[0]), parseFloat(coords[1]));

            let devicemap = new google.maps.Map(Element, {
                center: latlng,
                zoom: 12,
            });

            var devmarker = new google.maps.Marker({
                position: {
                    lat: devLat,
                    lng: devLong
                },
                label: {
                    text: coords[2],
                    color: "#d93025",
                    fontFamily: "Poppins",
                    className: "map-label"
                },
                map: devicemap
            });

            let infoDevMarker = new google.maps.Marker({
                position: {
                    lat: devLat + 0.001,
                    lng: devLong - 0.12
                },
                map: devicemap
            });

            let imgDevMarker = new google.maps.Marker({
                position: {
                    lat: devLat - 0.060,
                    lng: devLong - 0.12
                },
                map: devicemap
            });

            let upDevMarker = new google.maps.Marker({
                position: {
                    lat: devLat - 0.050,
                    lng: devLong + 0.1
                },
                map: devicemap
            });

            let downDevMarker = new google.maps.Marker({
                position: {
                    lat: devLat - 0.070,
                    lng: devLong - 0.001
                },
                map: devicemap
            });

            let id = $(Element).attr('id');

            setInterval(function() {
                $.ajax({
                    url: '/api/get-device/' + id,
                    type: 'GET',
                    success: function(result) {
                        let dataDevMarker = [devmarker, infoDevMarker, imgDevMarker, upDevMarker, downDevMarker]

                        getData(result.device, result.image, result.modbus, result.digital, result.history, devicemap, dataDevMarker, result.merge)
                    }
                })
            }, 3000)

            infoDevMarker.setVisible(false)
            imgDevMarker.setVisible(false)
            upDevMarker.setVisible(false)
            downDevMarker.setVisible(false)
        });
    }

    jQuery('.gm-style-iw').prev('div').remove();

    window.initMap = initFirstMap;
</script>
@endpush