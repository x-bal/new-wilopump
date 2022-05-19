@extends('layouts.master', ['title' => 'Dashbboard'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Dashboard</h2>

        @if(auth()->user()->level == 'Admin')
        <div id="map" style="width: 100%; height: 480px;" class="d-block w-100"></div>
        @else
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="5000">
                    <div id="first-map" style="width: 100%; height: 480px;" class="d-block w-100"></div>
                </div>
                @foreach($devices as $device)
                <div class="carousel-item" data-bs-interval="5000">
                    <div id="map-{{ $device->id }}" style="width: 100%; height: 480px;" class="d-block w-100"></div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        @endif
    </div>
</div>
@stop

@push('script')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $apikey }}&callback=initMap" defer></script>

@if(auth()->user()->level == 'Admin')
<script>
    let map;

    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: new google.maps.LatLng(-0.789275, 113.921327),
            zoom: 5,
        });

        $.ajax({
            url: '/api/get-device',
            type: 'GET',
            success: function(response) {
                $.each(response.devices, function(i, data) {
                    let latitude = parseFloat(data.lat);
                    let longitude = parseFloat(data.long);

                    const marker = new google.maps.Marker({
                        position: {
                            lat: latitude,
                            lng: longitude
                        },
                        // icon: icon,
                        animation: google.maps.Animation.DROP,
                        label: {
                            text: data.name,
                            fontFamily: "Arial",
                            color: "#000",
                            stroke: "#fff",
                            fontSize: "14px",
                        },
                        map: map,
                    });



                    marker.addListener("click", () => {
                        let id = data.id;

                        $.ajax({
                            url: '/api/get-device/' + id,
                            type: 'GET',
                            success: function(result) {
                                let device = result.device;

                                let contentString = '<div id="content"><div id="siteNotice"></div><h5 id="firstHeading" class="firstHeading">' + device.name + '</h5><div id="bodyContent"><p><b>Name : </b>' + device.name + '<br><b>Type : </b>' + device.type + '<br><b>Lat : </b>' + device.lat + '<br><b>Long : </b>' + device.long + '<br><a href="/device/' + id + '">More details</a></p></div></div>';

                                const infowindow = new google.maps.InfoWindow({
                                    content: contentString,
                                });

                                infowindow.open({
                                    anchor: marker,
                                    map,
                                    shouldFocus: false,
                                });
                            }
                        })

                    });
                })
            }
        })
    }

    window.initMap = initMap;
</script>
@else
<script>
    let map, deviceMap;

    function initFirstMap() {
        let lat = parseFloat("{{ $first->lat }}");
        let long = parseFloat("{{ $first->long }}");

        var myOptions = {
            zoom: 14,
            center: new google.maps.LatLng(lat, long),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }

        const map = new google.maps.Map(document.getElementById("first-map"), {
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

        @foreach($devices as $dev)
        let latdev = parseFloat("{{ $dev->lat }}");
        let longdev = parseFloat("{{ $dev->long }}");
        let iddev = parseFloat("{{ $dev->id }}");

        const deviceMap = new google.maps.Map(document.getElementById("map-" + iddev), {
            center: new google.maps.LatLng(latdev, longdev),
            zoom: 12,
        });

        const deviceMarker = new google.maps.Marker({
            position: {
                lat: latdev,
                lng: longdev
            },
            deviceMap,
        });
        @endforeach

        // marker.addListener("click", () => {
        //     let id = "{{ $first->id }}";

        //     $.ajax({
        //         url: '/api/get-device/' + id,
        //         type: 'GET',
        //         success: function(result) {
        //             let device = result.device;

        //             let contentString = '<div id="content"><div id="siteNotice"></div><h5 id="firstHeading" class="firstHeading">' + device.name + '</h5><div id="bodyContent"><p><b>Name : </b>' + device.name + '<br><b>Type : </b>' + device.type + '<br><b>Lat : </b>' + device.lat + '<br><b>Long : </b>' + device.long + '<br></p></div></div>';

        //             const infowindow = new google.maps.InfoWindow({
        //                 content: contentString,
        //             });

        //             infowindow.open({
        //                 anchor: marker,
        //                 map,
        //                 shouldFocus: true,
        //             });
        //         }
        //     })

        // });
    }

    window.initMap = initFirstMap;
</script>

<!-- @foreach($devices as $dev)
<script>
    let deviceMap;

    function initDeviceMap() {
        let lat = parseFloat("{{ $dev->lat }}");
        let long = parseFloat("{{ $dev->long }}");
        let id = parseFloat("{{ $dev->id }}");

        const deviceMap = new google.maps.Map(document.getElementById("map-" + id), {
            center: new google.maps.LatLng(lat, long),
            zoom: 12,
        });

        const marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: long
            },
            deviceMap,
        });
    }

    setInterval(function() {
        window.initMap = initDeviceMap;
    }, 0)
</script>
@endforeach -->
@endif
@endpush