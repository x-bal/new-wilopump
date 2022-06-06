@extends('layouts.master', ['title' => 'Chart'])

@section('content')
<div class="row mb-5">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Chart</h2>
    </div>

    <div class="col-md-5 mb-3">
        <form action="" class="form-chart">
            <select name="device" id="device" class="form-control">
                <option disabled selected>-- Select Device --</option>
                @foreach($devices as $dev)
                <option {{ request('device') == $dev->id ? 'selected' : '' }} value="{{ $dev->id }}">{{ $dev->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="col-md-2 ">
        <a href="" class="btn btn-success download-chart">Download</a>
    </div>

    <div class="col-md-12">
        <canvas id="myChart"></canvas>
    </div>
</div>
@stop

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $("#device").on('change', function() {
        let device = $(this).val();
        let labels = [];
        let dataset = [];

        $.ajax({
            url: '/api/get-device/' + device,
            type: 'GET',
            success: function(response) {
                $.each(response.modbus, function(i, data) {
                    labels.push(data.name)
                    dataset.push(data.after)
                })

                let data = {
                    labels: labels,
                    datasets: [{
                        label: response.device.name,
                        backgroundColor: 'rgb(0, 156, 130)',
                        borderColor: 'rgb(0, 156, 130)',
                        data: dataset,
                    }]
                };

                const config = {
                    type: 'line',
                    data: data,
                    options: {
                        animation: {
                            onComplete: function() {
                                var url_base64 = document.getElementById('myChart').toDataURL('image/png');
                                let name = response.device.name + '-chart.png';

                                $(".download-chart").attr('href', url_base64)
                                $(".download-chart").attr('download', name)
                            }
                        }
                    }
                };

                const myChart = new Chart(
                    document.getElementById('myChart'),
                    config
                );
            }
        })
    })
</script>

@endpush