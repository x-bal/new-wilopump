@extends('layouts.master', ['title' => 'Trend Grafik'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Trend Grafik</h2>
    </div>
</div>

<form action="" method="get" class="form-target">
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="device">Device</label>
            <select name="device" id="device" class="form-control">
                <option disabled selected>-- Select Device--</option>
                @foreach($devices as $dev)
                <option {{ request('device') == $dev->id ? 'selected' : '' }} value="{{ $dev->id }}">{{ $dev->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="from">From</label>
            <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-3">
            <label for="to">To</label>
            <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
        </div>
        @if(request('device'))
        <div class="col-md-3 mt-5">
            <a href="{{ route('export') }}?device={{ request('device') }}&from={{ request('from') }}&to={{ request('to') }}" class="btn btn-success">Export</a>
        </div>
        @endif
    </div>
</form>

<div class="row mb-5">
    <div class="col-md-6">
        <h5 class="mb-2">History Modbus</h5>
        <div id="tableModbus" data-list='{"page":10,"pagination":true}'>

            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort text-center" data-sort="no">No</th>
                            <th class="sort" data-sort="time">Time</th>
                            <th class="sort" data-sort="name">Name</th>
                            <th class="sort" data-sort="val">Val</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="list-modbus">
                        @if(request('device'))
                        @foreach($modbuses as $modbus)
                        <tr>
                            <td class="no">{{ $loop->iteration }}</td>
                            <td class="time">{{ Carbon\Carbon::parse($modbus->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="name">{{ $modbus->modbus->name }}</td>
                            <td class="val">{{ $modbus->val }}</td>
                        </tr>
                        @endforeach
                        @endif
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

    <div class="col-md-6">
        <h5 class="mb-2">History Digital Input</h5>
        <div id="tableDigital" data-list='{"valueNames":["no","name","val","time"],"page":10,"pagination":true}'>

            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort text-center" data-sort="no">No</th>
                            <th class="sort" data-sort="time">Time</th>
                            <th class="sort" data-sort="name">Name</th>
                            <th class="sort" data-sort="val">Val</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @if(request('device'))
                        @foreach($digital as $digi)
                        <tr>
                            <td class="no">{{ $loop->iteration }}</td>
                            <td class="time">{{ Carbon\Carbon::parse($digi->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="name">{{ $digi->digital->name }}</td>
                            <td class="val">{{ $digi->val }}</td>
                        </tr>
                        @endforeach
                        @endif
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

<div class="row mb-5 ">
    @foreach($active as $act)
    <div class="col-md-6 chart-{{ $act->id }}">
        <canvas id="myChart-{{ $act->id }}"></canvas>
    </div>
    @endforeach
</div>
@stop

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $("#device").on('change', function() {
        $(".form-target").submit()
    })

    $("#to").on('change', function() {
        $(".form-target").submit()
    })
</script>

@if(request('device'))
<script>
    let id = "{{ request('device') }}"
    let labels = '';
    let dataset = '';
    let no = 1;
    let set = [];
    let config = [];
    $.ajax({
        url: '/api/get-history-modbus/' + id,
        type: 'GET',
        data: {
            from: "{{ request('from') }}",
            to: "{{ request('to') }}",
        },
        success: function(response) {
            $.each(response.history, function(i, data) {
                $.each(data.histories, function(index, his) {
                    labels += his.id
                    dataset = his.val
                });
                console.log(dataset)


                set = {
                    labels: labels,
                    datasets: [{
                        label: data.name,
                        backgroundColor: 'rgb(0, 156, 130)',
                        borderColor: 'rgb(0, 156, 130)',
                        data: dataset,
                    }]
                };

                config = {
                    type: 'line',
                    data: set,
                    options: {
                        // animation: {
                        //     onComplete: function() {
                        //         var url_base64 = document.getElementById('myChart').toDataURL('image/png');
                        //         let name = "{{ $device->name }}" + '-chart.png';

                        //         $(".download-chart").attr('href', url_base64)
                        //         $(".download-chart").attr('download', name)
                        //     }
                        // }
                    }
                };

                let myChart = document.getElementById('myChart-' + data.id)
                myChart.remove();

                const canvas = document.createElement("canvas");
                canvas.setAttribute("id", "myChart-" + data.id);
                canvas.setAttribute('width', '1007');
                canvas.setAttribute('height', '503');
                canvas.setAttribute('style', 'display: block; box-sizing: border-box; height: 64vh; width: 35vw;');
                $(".chart-" + data.id).append(canvas)

                myChart = new Chart(
                    document.getElementById('myChart-' + data.id),
                    config
                );
            })



        }
    })
</script>
@endif
@endpush