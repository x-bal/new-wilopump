@extends('layouts.master', ['title' => 'Dashbboard'])

@push('style')
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
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
        background-color: #009c81;
    } */
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Dashboard</h2>

        <div id="map" style="width: 100%; height: 480px;" class="d-block w-100"></div>
    </div>
</div>
@stop

@push('script')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $apikey }}&callback=initMap" defer></script>


<script>
    let level = "{{ auth()->user()->level }}";

    let map;

    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: new google.maps.LatLng(-0.789275, 113.921327),
            zoom: 5,
        });

        $.ajax({
            url: '/get-device',
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
                            fontFamily: "Poppins",
                            className: "map-label"
                        },
                        zIndex: i,
                        map: map,
                    });



                    marker.addListener("click", () => {
                        let id = data.id;

                        $.ajax({
                            url: '/api/get-device/' + id,
                            type: 'GET',
                            success: function(result) {
                                let device = result.device;

                                let contentString = `<div class="card">
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
                                        <td>` + result.history + `</td>
                                    </tr>`
                                if (level == 'Admin') {
                                    contentString += `<tr>
                                                <td colspan="3" class="text-center"><a href="/device/` + device.id + `">More Detail</a></td>
                                            </tr>
                                        </table>
                                    </div>`
                                }


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
@endpush