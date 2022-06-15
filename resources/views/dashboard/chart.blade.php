@extends('layouts.master', ['title' => 'Chart'])

@section('content')
<div class="row mb-5">
    <div class="col-md-12">
        <h2 class="lh-sm">Chart</h2>
    </div>


</div>

<form action="" class="form-chart mb-3">
    <div class="row">
        <div class="col-md-3">
            <label for="device">Device</label>
            <select name="device" id="device" class="form-control">
                <option disabled selected>-- Select Device --</option>
                @foreach($devices as $dev)
                <option {{ request('device') == $dev->id ? 'selected' : '' }} value="{{ $dev->id }}">{{ $dev->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="data">Data</label>
            <select name="data" id="data" class="form-control modbus">
                <option disabled selected>-- Select Data --</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="from">From</label>
            <input type="date" name="from" id="from" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="to">To</label>
            <input type="date" name="to" id="to" class="form-control">
        </div>
    </div>
</form>

<div class="row mb-3">
    <div class="col-md-12 chart">
        <canvas id="myChart"></canvas>
    </div>
</div>
@stop

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let device = '';

    $("#device").on('change', function() {
        let id = $(this).val();

        $.ajax({
            url: '/api/get-device/' + id,
            type: 'GET',
            success: function(response) {
                let modbus = response.modbus;
                device = response.device.name;

                $(".modbus").empty();
                $(".modbus").append('<option disabled selected>-- Select Data --</option>')

                $.each(modbus, function(i, data) {
                    $(".modbus").append(`<option value="` + data.id + `">` + data.name + `</option>`)
                })
            }
        })
    });

    $(document).ready(function() {
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

        let labels = [];
        let dataset = [];
        let idm = '';
        let from = '';
        let to = '';

        function getHistory(id, from, to) {
            $.ajax({
                url: '/api/get-modbus/' + id,
                type: 'GET',
                data: {
                    from: from,
                    to: to,
                },
                success: function(response) {

                    $.each(response.history, function(i, data) {
                        let date = parseTime(data.created_at)

                        labels.push(date)
                        dataset.push(data.val)
                    })

                    let data = {
                        labels: labels,
                        datasets: [{
                            label: response.modbus.name + " (" + response.modbus.satuan + ")",
                            backgroundColor: 'rgb(0, 156, 130)',
                            borderColor: 'rgb(0, 156, 130)',
                            data: dataset,
                        }]
                    };

                    let config = {
                        type: 'line',
                        data: data,
                        options: {
                            animation: {
                                onComplete: function() {
                                    var url_base64 = document.getElementById('myChart').toDataURL('image/png');
                                    let name = response.modbus.name + '-chart.png';

                                    $(".download-chart").attr('href', url_base64)
                                    $(".download-chart").attr('download', name)
                                }
                            }
                        }
                    };

                    let myChart = document.getElementById('myChart')
                    myChart.remove();

                    const canvas = document.createElement("canvas");
                    canvas.setAttribute("id", "myChart");
                    canvas.setAttribute('width', '1007');
                    canvas.setAttribute('height', '503');
                    canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
                    $(".chart").append(canvas)

                    myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );
                }
            })
        }

        $(".modbus").on('change', function() {
            idm = $(this).val()

            getHistory(idm, from, to)

        });

        $("#to").on('change', function() {
            from = $("#from").val();
            to = $("#to").val();
            labels = [];
            dataset = [];

            getHistory(idm, from, to)
        });
    })
</script>
@endpush