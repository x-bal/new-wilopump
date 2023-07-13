@extends('layouts.master', ['title' => 'Access Viewer'])
@push('style')

<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />

@endpush

@section('content')
<h1 class="page-header">Access Viewer</h1>

<div class="panel panel-inverse">
    <!-- BEGIN panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">Access Viewer</h4>
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
                <a href="{{ route('access.create') }}" class="btn btn-success mb-3">Give Access</a>

                <div class="table-responsive scrollbar">
                    <table class="table table-bordered table-striped table-access fs--1 mb-0" id="dataTable">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="sort" data-sort="no">No</th>
                                <th class="sort" data-sort="image">Image</th>
                                <th class="sort" data-sort="name">Name</th>
                                <th class="sort" data-sort="device">Device</th>
                                <th class="sort" data-sort="action">Action</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($viewers as $viewer)
                            <tr>
                                <td class="no">{{ $loop->iteration }}</td>
                                <td>
                                    <img class="rounded-circle" src="{{ asset('storage/' . $viewer->image) }}" alt="" width="40">
                                </td>
                                <td class="name">{{ $viewer->name }}</td>
                                <td class="device">{{ $viewer->name }}</td>
                                <td class="action">
                                    <a href="{{ route('access.edit', $viewer->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
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