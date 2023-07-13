@extends('layouts.master', ['title' => 'Data Device'])

@push('style')

<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />

@endpush

@section('content')
<div class="col-md-12">
    <h1 class="page-header">Data Device</h1>

    <div class="panel panel-inverse">
        <!-- BEGIN panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">Data Device</h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <!-- END panel-heading -->
        <!-- BEGIN panel-body -->
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">

                    <div id="tableExample2" data-list='{"valueNames":["no","img","name","id","type","lat","long","is_active", "action"],"page":10,"pagination":true}'>

                        @if(auth()->user()->level == 'Admin')
                        <a href="{{ route('device.create') }}" class="btn btn-sm btn-success mb-3">Add Device</a>
                        @endif

                        <table id="dataTable" class="table table-striped table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th class="text-nowrap">Foto</th>
                                    <th class="text-nowrap">ID Device</th>
                                    <th class="text-nowrap">Name</th>
                                    <th class="text-nowrap">Type</th>
                                    <th class="text-nowrap">Lat</th>
                                    <th class="text-nowrap">Long</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($devices as $device)
                                <tr>
                                    <td class="no">{{ $loop->iteration }}</td>
                                    <td class="img"><img src="{{ asset('/storage/'.$device->image) }}" alt="" width="70px"></td>
                                    <td class="id">{{ $device->iddev }}</td>
                                    <td class="name">{{ $device->name }}</td>
                                    <td class="type">{{ $device->type }}</td>
                                    <td class="lat">{{ $device->lat }}</td>
                                    <td class="long">{{ $device->long }}</td>
                                    <td class="is_active">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input device-active" data-id="{{ $device->id }}" type="checkbox" name="active" {{ $device->is_active == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label label-{{ $device->id }}" for="active">{{ $device->is_active == 1 ? 'Active' : 'Nonactive' }}</label>
                                        </div>
                                    </td>
                                    <td class="action">
                                        <a href="{{ route('device.show', $device->id) }}" title="Show Device" class="btn btn-sm btn-secondary mr-2"><i class="fas fa-eye"></i></a>

                                        <a href="{{ route('device.grafik', $device->id) }}" title="Grafik Device" class="btn btn-sm btn-warning mr-2"><i class="fas fa-chart-line"></i></a>

                                        <a href="{{ route('device.edit', $device->id) }}" title="Edit Device" class="btn btn-sm btn-info mr-2"><i class="fas fa-edit"></i></a>

                                        <a href="{{ route('device.reset', $device->id) }}" title="Reset Device" class="btn btn-sm btn-success mr-2" onclick="return confirm('Reset device ?')"><i class="fas fa-sync"></i></a>

                                        <form action="{{ route('device.destroy', $device->id) }}" method="post" style="display: inline;">
                                            @method('DELETE')
                                            @csrf

                                            <button type="submit" title="Delete Device" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $("#dataTable").DataTable()
    })
</script>
@endpush