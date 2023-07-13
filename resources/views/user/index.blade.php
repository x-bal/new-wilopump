@extends('layouts.master', ['title' => 'Data User'])

@push('style')

<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />

@endpush

@section('content')
<div class="col-md-12">
    <h1 class="page-header">Data User</h1>

    <div class="panel panel-inverse">
        <!-- BEGIN panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">Data Table - Default</h4>
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

            <a href="{{ route('user.create') }}" class="btn btn-sm btn-success mb-3">Add User</a>

            <table id="dataTable" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th width="1%">#</th>
                        <th class="text-nowrap">Foto</th>
                        <th class="text-nowrap">Name</th>
                        <th class="text-nowrap">Email</th>
                        <th class="text-nowrap">Level</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="no">{{ $loop->iteration }}</td>
                        <td>
                            <img class="rounded-circle" src="{{ asset('storage/' . $user->image) }}" alt="" width="70">
                        </td>
                        <td class="name">{{ $user->name }}</td>
                        <td class="email">{{ $user->email }}</td>
                        <td class="level">{{ $user->level }}</td>
                        <td class="action">
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-info mr-2"><i class="fas fa-edit"></i></a>

                            <form action="{{ route('user.destroy', $user->id) }}" method="post" style="display: inline;">
                                @method('DELETE')
                                @csrf

                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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