@extends('layouts.master', ['title' => 'Dashbboard'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Dashboard</h2>

        <div id="map" style="width: 100%; height: 480px;"></div>
    </div>

    <div class="col-md-12"></div>
</div>
@stop

@push('script')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $apikey }}&callback=initMap" defer></script>

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


        // const marker = new google.maps.Marker({
        //     position: uluru,
        //     map,
        //     title: "Uluru (Ayers Rock)",
        // });



        // for (let i = 0; i < features.length; i++) {
        //     const marker = new google.maps.Marker({
        //         position: features[i].position,
        //         icon: icons[features[i].type].icon,
        //         map: map,
        //     });
        // }
    }

    window.initMap = initMap;
</script>
@endpush