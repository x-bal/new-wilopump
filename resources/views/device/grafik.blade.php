@extends('layouts.master', ['title' => 'Trend Grafik Device'])
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

    /* .gm-style .gm-style-iw {} */
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class=" text-center lh-sm">Pump Performance Data</h2>

        <h4 class="text-center mb-3">{{ $device->name }}</h4>

        <div id="map" style="width: 100%; height: 480px;"></div>
    </div>
</div>


<div class="row my-3">
    <h4 class="text-center my-3">Trend Grafik</h4>

    <div class="col-md-6 mb-3 chart-1">
        <canvas id="chart-1" width="100%"></canvas>
    </div>
    <div class="col-md-6 mb-3 chart-2">
        <canvas id="chart-2" width="100%"></canvas>
    </div>
    <div class="col-md-6 mb-3 chart-3">
        <canvas id="chart-3" width="100%"></canvas>
    </div>
    <div class="col-md-6 mb-3 chart-4">
        <canvas id="chart-4" width="100%"></canvas>
    </div>
    <div class="col-md-6 mb-3 chart-5">
        <canvas id="chart-5" width="100%"></canvas>
    </div>
    <div class="col-md-6 mb-3 chart-6">
        <canvas id="chart-6" width="100%"></canvas>
    </div>
</div>
@stop

@push('script')
<script src="{{ asset('/js/script.js') }}"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $apikey }}&callback=initMap" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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


        let id = "{{ $device->id }}";

        $.ajax({
            url: '/api/get-device/' + id,
            type: 'GET',
            success: function(result) {
                let dataMarker = [marker, infoMarker, imgMarker, upMarker, downMarker];
                getData(result.device, result.image, result.modbus, result.digital, result.history, map, dataMarker, result.merge)
            }
        })

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
                    }
                    upFirst += `</tr>`;
                })

                if (merge.length > 0) {
                    $.each(merge, function(i, data) {
                        upFirst += `<tr>
                                    <td>` + data.name + `</td>
                                    <td> : </td>`;
                        if (data.after == null) {
                            upFirst += `<td>` + data.val + data.unit + `</td>`;
                        } else {
                            upFirst += `<td>` + data.val + data.unit + `</td>`;
                        }
                    })
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
    }

    window.initMap = initMap;
</script>

<script>
    let id = "{{ $device->id }}"
    let labelsOne = [];
    let labelsTwo = [];
    let labelsThree = [];
    let labelsFour = [];
    let labelsFive = [];
    let labelsSix = [];
    let datasetOne = [];
    let datasetTwo = [];
    let datasetThree = [];
    let datasetFour = [];
    let datasetFive = [];
    let datasetSix = [];

    function parseTime(dateTime) {
        let timestamp = new Date(dateTime);
        let date = timestamp.getDate();
        let month = timestamp.getMonth();
        let year = timestamp.getFullYear();
        let hours = timestamp.getHours();
        let minute = timestamp.getMinutes();
        let second = timestamp.getSeconds();

        return date + '/' + (month + 1) + '/' + year + ' ' + hours + ':' + minute + ':' + second
    }

    $.ajax({
        url: '/api/get-history-modbus/' + id,
        type: 'GET',
        success: function(response) {
            let modbus = response.history;

            if (modbus[0]) {
                $.each(modbus[0].histories, function(i, data) {
                    let time = parseTime(data.created_at)

                    labelsOne.push(time)
                    datasetOne.push(data.val)
                })

                let dataOne = {
                    labels: labelsOne,
                    datasets: [{
                        label: modbus[0].name,
                        backgroundColor: 'rgb(0, 156, 130)',
                        borderColor: 'rgb(0, 156, 130)',
                        data: datasetOne,
                    }]
                };

                let config = {
                    type: 'line',
                    data: dataOne,
                    options: {
                        // animation: {
                        //     onComplete: function() {
                        //         var url_base64 = document.getElementById('myChart').toDataURL('image/png');
                        //         let name = modbus[0].name + '-chart.png';

                        //         $(".download-chart").attr('href', url_base64)
                        //         $(".download-chart").attr('download', name)
                        //     }
                        // }
                    }
                };

                let chartOne = document.getElementById('chart-1')
                chartOne.remove();

                const canvas = document.createElement("canvas");
                canvas.setAttribute("id", "chart-1");
                canvas.setAttribute('width', '1007');
                canvas.setAttribute('height', '503');
                canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
                $(".chart-1").append(canvas)

                chartOne = new Chart(
                    document.getElementById('chart-1'),
                    config
                );
            }

            if (modbus[1]) {
                $.each(modbus[1].histories, function(i, data) {
                    let time = parseTime(data.created_at)

                    labelsTwo.push(time)
                    datasetTwo.push(data.val)
                })

                let dataTwo = {
                    labels: labelsTwo,
                    datasets: [{
                        label: modbus[1].name,
                        backgroundColor: 'rgb(0, 156, 130)',
                        borderColor: 'rgb(0, 156, 130)',
                        data: datasetTwo,
                    }]
                };

                let config = {
                    type: 'line',
                    data: dataTwo,
                    options: {
                        // animation: {
                        //     onComplete: function() {
                        //         var url_base64 = document.getElementById('myChart').toDataURL('image/png');
                        //         let name = modbus[0].name + '-chart.png';

                        //         $(".download-chart").attr('href', url_base64)
                        //         $(".download-chart").attr('download', name)
                        //     }
                        // }
                    }
                };

                let chartTwo = document.getElementById('chart-2')
                chartTwo.remove();

                const canvas = document.createElement("canvas");
                canvas.setAttribute("id", "chart-2");
                canvas.setAttribute('width', '1007');
                canvas.setAttribute('height', '503');
                canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
                $(".chart-2").append(canvas)

                chartTwo = new Chart(
                    document.getElementById('chart-2'),
                    config
                );
            }

            if (modbus[2]) {
                $.each(modbus[2].histories, function(i, data) {
                    let time = parseTime(data.created_at)

                    labelsThree.push(time)
                    datasetThree.push(data.val)
                })

                let dataThree = {
                    labels: labelsThree,
                    datasets: [{
                        label: modbus[2].name,
                        backgroundColor: 'rgb(0, 156, 130)',
                        borderColor: 'rgb(0, 156, 130)',
                        data: datasetThree,
                    }]
                };

                let config = {
                    type: 'line',
                    data: dataThree,
                    options: {
                        // animation: {
                        //     onComplete: function() {
                        //         var url_base64 = document.getElementById('myChart').toDataURL('image/png');
                        //         let name = modbus[0].name + '-chart.png';

                        //         $(".download-chart").attr('href', url_base64)
                        //         $(".download-chart").attr('download', name)
                        //     }
                        // }
                    }
                };

                let chartThree = document.getElementById('chart-3')
                chartThree.remove();

                const canvas = document.createElement("canvas");
                canvas.setAttribute("id", "chart-3");
                canvas.setAttribute('width', '1007');
                canvas.setAttribute('height', '503');
                canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
                $(".chart-3").append(canvas)

                chartThree = new Chart(
                    document.getElementById('chart-3'),
                    config
                );
            }

            if (modbus[3]) {
                $.each(modbus[3].histories, function(i, data) {
                    let time = parseTime(data.created_at)

                    labelsFour.push(time)
                    datasetFour.push(data.val)
                })

                let dataFour = {
                    labels: labelsFour,
                    datasets: [{
                        label: modbus[3].name,
                        backgroundColor: 'rgb(0, 156, 130)',
                        borderColor: 'rgb(0, 156, 130)',
                        data: datasetFour,
                    }]
                };

                let config = {
                    type: 'line',
                    data: dataFour,
                    options: {
                        // animation: {
                        //     onComplete: function() {
                        //         var url_base64 = document.getElementById('myChart').toDataURL('image/png');
                        //         let name = modbus[0].name + '-chart.png';

                        //         $(".download-chart").attr('href', url_base64)
                        //         $(".download-chart").attr('download', name)
                        //     }
                        // }
                    }
                };

                let chartFour = document.getElementById('chart-4')
                chartFour.remove();

                const canvas = document.createElement("canvas");
                canvas.setAttribute("id", "chart-4");
                canvas.setAttribute('width', '1007');
                canvas.setAttribute('height', '503');
                canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
                $(".chart-4").append(canvas)

                chartFour = new Chart(
                    document.getElementById('chart-4'),
                    config
                );
            }

            if (modbus[4]) {
                $.each(modbus[4].histories, function(i, data) {
                    let time = parseTime(data.created_at)

                    labelsFive.push(time)
                    datasetFive.push(data.val)
                })

                let dataFive = {
                    labels: labelsFive,
                    datasets: [{
                        label: modbus[4].name,
                        backgroundColor: 'rgb(0, 156, 130)',
                        borderColor: 'rgb(0, 156, 130)',
                        data: datasetFive,
                    }]
                };

                let config = {
                    type: 'line',
                    data: dataFive,
                    options: {
                        // animation: {
                        //     onComplete: function() {
                        //         var url_base64 = document.getElementById('myChart').toDataURL('image/png');
                        //         let name = modbus[0].name + '-chart.png';

                        //         $(".download-chart").attr('href', url_base64)
                        //         $(".download-chart").attr('download', name)
                        //     }
                        // }
                    }
                };

                let chartFive = document.getElementById('chart-5')
                chartFive.remove();

                const canvas = document.createElement("canvas");
                canvas.setAttribute("id", "chart-5");
                canvas.setAttribute('width', '1007');
                canvas.setAttribute('height', '503');
                canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
                $(".chart-5").append(canvas)

                chartFive = new Chart(
                    document.getElementById('chart-5'),
                    config
                );
            }

            if (modbus[5]) {
                $.each(modbus[5].histories, function(i, data) {
                    let time = parseTime(data.created_at)

                    labelsSix.push(time)
                    datasetSix.push(data.val)
                })

                let dataSix = {
                    labels: labelsSix,
                    datasets: [{
                        label: modbus[5].name,
                        backgroundColor: 'rgb(0, 156, 130)',
                        borderColor: 'rgb(0, 156, 130)',
                        data: datasetSix,
                    }]
                };

                let config = {
                    type: 'line',
                    data: dataSix,
                    options: {
                        // animation: {
                        //     onComplete: function() {
                        //         var url_base64 = document.getElementById('myChart').toDataURL('image/png');
                        //         let name = modbus[0].name + '-chart.png';

                        //         $(".download-chart").attr('href', url_base64)
                        //         $(".download-chart").attr('download', name)
                        //     }
                        // }
                    }
                };

                let chartSix = document.getElementById('chart-6')
                chartSix.remove();

                const canvas = document.createElement("canvas");
                canvas.setAttribute("id", "chart-6");
                canvas.setAttribute('width', '1007');
                canvas.setAttribute('height', '503');
                canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
                $(".chart-6").append(canvas)

                chartSix = new Chart(
                    document.getElementById('chart-6'),
                    config
                );
            }

        }
    })
</script>
@endpush