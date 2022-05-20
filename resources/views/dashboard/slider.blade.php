@extends('layouts.master', ['title' => 'Slider'])

@push('style')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Slider</h2>

        <div id="carouselExampleControls" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div class="carousel-inner">
                        <div class=" carousel-item active" data-bs-interval="3000">
                            <div id="first-map" style="width: 100%; height: 480px;" class="d-block w-100"></div>
                        </div>
                        @foreach($devices as $device)
                        <div class="carousel-item" data-bs-interval="5000">
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
                fontFamily: "Arial",
                color: "#000",
                stroke: "#fff",
                fontSize: "14px",
            },
            map: map,
        });

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
        });
        // @foreach($devices as $dev)
        // let latdev = parseFloat("{{ $dev->lat }}");
        // let longdev = parseFloat("{{ $dev->long }}");
        // let iddev = parseFloat("{{ $dev->id }}");

        // const deviceMap = new google.maps.Map(document.getElementById("map-" + iddev), {
        //     center: new google.maps.LatLng(parseFloat("{{ $dev->lat }}"), parseFloat("{{ $dev->long }}")),
        //     zoom: 12,
        // });

        // const deviceMarker = new google.maps.Marker({
        //     position: {
        //         lat: latdev,
        //         lng: longdev
        //     },
        //     label: {
        //         text: "{{ $dev->name }}",
        //         fontFamily: "Arial",
        //         color: "#000",
        //         stroke: "#fff",
        //         fontSize: "14px",
        //     },
        //     map: deviceMap,
        // });
        // @endforeach

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
@endpush