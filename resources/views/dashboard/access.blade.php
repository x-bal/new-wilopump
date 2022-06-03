@extends('layouts.master', ['title' => 'Access Viewer'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Access Viewer</h2>

        <div id="tableExample2" data-list='{"valueNames":["no","image","name","device","level", "action"],"page":10,"pagination":true}'>

            <a href="{{ route('access.create') }}" class="btn btn-success mb-3">Give Access</a>

            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped table-access fs--1 mb-0">
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
                            <td class="image">
                                <div class="avatar avatar-xl avatar-bordered me-4">
                                    <img class="rounded-circle" src="{{ asset('storage/' . $viewer->image) }}" alt="">
                                </div>
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
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="pagination mb-0"></ul>
                <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
            </div>
        </div>
    </div>
</div>
@stop