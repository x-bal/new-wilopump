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

@php
$no = 1;
@endphp
<div class="row my-3">
    <h4 class="text-center mb-3">Pump History</h4>

    <form action="{{ route('export') }}" class="row mb-3 d-flex justify-content-center">
        @csrf
        <input type="hidden" name="device" value="{{ $device->id }}">
        <div class="col-md-5">
            <div class="form-group">
                <label for="from">From</label>
                <input type="date" name="from" id="from" class="form-control">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="from">From</label>
                <input type="date" name="from" id="from" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mt-5">
                <button type="submit" class="btn btn-success">Export</button>
            </div>
        </div>
    </form>

    <div class="col-md-12">
        <div id="tableExample2" data-list='{"valueNames":["no","date","name","id","type","lat","long","is_active", "action"],"page":10,"pagination":true}'>

            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900 bg-success text-white">
                        <tr>
                            <th class="sort" data-sort="no">No</th>
                            <th class="sort" data-sort="date">Date</th>
                            @foreach($digital as $dig)
                            <th class="sort" data-sort="id">{{ $dig->name }}</th>
                            @endforeach
                            @foreach($modbus as $mod)
                            <th class="sort" data-sort="id">{{ $mod->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach($history as $hd)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ Carbon\Carbon::parse($hd->created_at)->format('d/m/Y H:i:s') }}</td>
                            @foreach(App\Models\History::where('time', $hd->time)->whereHas('digital', function($q){
                            $q->where('is_used', 1);
                            })->get() as $dig)
                            <td>
                                {{ $dig->val == 1 ? $dig->digital->yes : $dig->digital->no }}
                            </td>
                            @endforeach
                            @foreach(App\Models\History::where('time', $hd->time)->whereHas('modbus', function($q){
                            $q->where('is_used', 1);
                            })->get() as $mod)
                            <td>{{ $mod->val }}</td>
                            @endforeach
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

        function getMap() {

            $.ajax({
                url: '/api/get-device/' + id,
                type: 'GET',
                success: function(result) {
                    let dataMarker = [marker, infoMarker, imgMarker, upMarker, downMarker];
                    getData(result.device, result.image, result.modbus, result.digital, result.history, map, dataMarker, result.merge)
                }
            })
        }

        getMap();

        setInterval(function() {
            getMap()
        }, 30000)

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
                            upFirst += `<td>` + data.after + data.satuan + `</td>`;
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
                            upFirst += `<td>` + data.after + data.unit + `</td>`;
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

    function getChart() {
        $.ajax({
            url: '/api/get-history-modbus/' + id,
            type: 'GET',
            success: function(response) {
                let active = response.active;
                let history = response.history;
                let digital = response.digital;
                console.log(active)

                labelsOne = [];
                labelsTwo = [];
                labelsThree = [];
                labelsFour = [];
                labelsFive = [];
                labelsSix = [];
                datasetOne = [];
                datasetTwo = [];
                datasetThree = [];
                datasetFour = [];
                datasetFive = [];
                datasetSix = [];

                if (active[0]) {
                    $.each(active[0].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[0].name + ' (' + active[0].satuan + ')';

                        labelsOne.push(time)
                        datasetOne.push(data.val)

                        createChart('chart-1', 'chart-1', labelsOne, datasetOne, labelName)
                    });
                }

                if (active[1]) {
                    $.each(active[1].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[1].name + ' (' + active[1].satuan + ')';

                        labelsTwo.push(time)
                        datasetTwo.push(data.val)

                        createChart('chart-2', 'chart-2', labelsTwo, datasetTwo, labelName)
                    })

                }

                if (active[2]) {
                    $.each(active[2].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[2].name + ' (' + active[2].satuan + ')';

                        labelsThree.push(time)
                        datasetThree.push(data.val)

                        createChart('chart-3', 'chart-3', labelsThree, datasetThree, labelName)
                    })

                }

                if (active[3]) {
                    $.each(active[3].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[3].name + ' (' + active[3].satuan + ')';

                        labelsFour.push(time)
                        datasetFour.push(data.val)

                        createChart('chart-4', 'chart-4', labelsFour, datasetFour, labelName)
                    })
                }

                if (active[4]) {
                    $.each(active[4].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[4].name + ' (' + active[4].satuan + ')';

                        labelsFive.push(time)
                        datasetFive.push(data.val)

                        createChart('chart-5', 'chart-5', labelsFive, datasetFive, labelName)
                    })
                }

                if (active[5]) {
                    $.each(active[5].histories, function(i, data) {
                        let time = parseTime(data.created_at)
                        let labelName = active[5].name + ' (' + active[5].satuan + ')';

                        labelsSix.push(time)
                        datasetSix.push(data.val)

                        createChart('chart-6', 'chart-6', labelsSix, datasetSix, labelName)
                    })
                }
            }
        })
    }

    getChart()

    setInterval(function() {
        getChart()
    }, 30000)

    function createChart(ctxid, ctxclass, labels, dataset, label) {
        $("#" + ctxid).remove();
        const canvas = document.createElement("canvas");
        canvas.setAttribute("id", ctxid);
        canvas.setAttribute('width', '1007');
        canvas.setAttribute('height', '503');
        canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
        $("." + ctxclass).append(canvas)

        let myChart = new Chart($("#" + ctxid), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: dataset,
                    backgroundColor: 'rgb(0, 156, 130)',
                    borderColor: 'rgb(0, 156, 130)',
                }]
            }
        });
    }
</script>
@endpush