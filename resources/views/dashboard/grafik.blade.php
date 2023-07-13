@extends('layouts.master', ['title' => 'View Data Device'])

@push('style')

<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />

@endpush

@section('content')
<h1 class="page-header">View Data Device</h1>

<div class="panel panel-inverse">
    <!-- BEGIN panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">View Data Device</h4>
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

                <div class="table-responsive scrollbar">
                    <table class="table table-bordered table-striped fs--1 mb-0" id="dataTable">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="sort" data-sort="no">No</th>
                                <th class="sort" data-sort="img">Image</th>
                                <th class="sort" data-sort="id">Id Device</th>
                                <th class="sort" data-sort="name">Name</th>
                                <th class="sort" data-sort="type">Type</th>
                                <th class="sort" data-sort="lat">Lat</th>
                                <th class="sort" data-sort="long">Long</th>
                                <th class="sort" data-sort="action">Action</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($devices as $device)
                            <tr>
                                <td class="no">{{ $loop->iteration }}</td>
                                <td class="img"><img src="{{ asset('/storage/'.$device->image) }}" alt="" width="70px"></td>
                                <td class="id">{{ $device->iddev }}</td>
                                <td class="name">{{ $device->name }}</td>
                                <td class="type">{{ $device->type }}</td>
                                <td class="lat">{{ $device->lat }}</td>
                                <td class="long">{{ $device->long }}</td>
                                <td class="action">
                                    <a href="{{ route('device.grafik', $device->id) }}" class="btn btn-sm btn-warning mr-2"><i class="fas fa-chart-line"></i></a>

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