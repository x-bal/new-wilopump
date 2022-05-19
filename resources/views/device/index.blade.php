@extends('layouts.master', ['title' => 'Data Device'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Data Device</h2>

        <div id="tableExample2" data-list='{"valueNames":["no","name","id","type","lat","long","is_active", "action"],"page":10,"pagination":true}'>

            <a href="{{ route('device.create') }}" class="btn btn-sm btn-success mb-3">Add Device</a>

            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort" data-sort="no">No</th>
                            <th class="sort" data-sort="id">Id Device</th>
                            <th class="sort" data-sort="name">Name</th>
                            <th class="sort" data-sort="type">Type</th>
                            <th class="sort" data-sort="lat">Lat</th>
                            <th class="sort" data-sort="long">Long</th>
                            <th class="sort" data-sort="action">Action</th>
                            <th class="sort" data-sort="is_active">Active</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach($devices as $device)
                        <tr>
                            <td class="no">{{ $loop->iteration }}</td>
                            <td class="id">{{ $device->id }}</td>
                            <td class="name">{{ $device->name }}</td>
                            <td class="type">{{ $device->type }}</td>
                            <td class="lat">{{ $device->lat }}</td>
                            <td class="long">{{ $device->long }}</td>
                            <td class="action">
                                <a href="{{ route('device.show', $device->id) }}" class="btn btn-sm btn-secondary mr-2"><i class="fas fa-eye"></i></a>

                                <a href="{{ route('device.edit', $device->id) }}" class="btn btn-sm btn-info mr-2"><i class="fas fa-edit"></i></a>

                                <form action="{{ route('device.destroy', $device->id) }}" method="post" style="display: inline;">
                                    @method('DELETE')
                                    @csrf

                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                            <td class="is_active">
                                <div class="form-check form-switch">
                                    <input class="form-check-input device-active" data-id="{{ $device->id }}" type="checkbox" name="used" {{ $device->is_active == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="used">{{ $device->is_active == 1 ? 'Active' : 'Nonaktif' }}</label>
                                </div>
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