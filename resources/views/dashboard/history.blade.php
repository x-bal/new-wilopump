@extends('layouts.master', ['title' => 'History'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">History Device</h2>
    </div>

    <div class="col-md-12">
        <div id="tableModbus" data-list='{"valueNames":["no","device","desc","val","time"],"page":10,"pagination":true}'>

            <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Export to Excel
            </button>

            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort text-center" data-sort="no">No</th>
                            <th class="sort" data-sort="time">Time</th>
                            <th class="sort" data-sort="device">Device</th>
                            <th class="sort" data-sort="val">Val</th>
                            <th class="sort" data-sort="desc">Desc</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach($histories as $history)
                        <tr>
                            <td class="no text-center">{{ $loop->iteration }}</td>
                            <td class="time">{{ Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="device">{{ $history->device->name }}</td>
                            <td class="val">{{ $history->val }}</td>
                            <td class="desc">{{ $history->ket }}</td>
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Export to Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('export') }}" method="get">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="device">Device</label>
                        <select name="device" id="device" class="form-control">
                            <option disabled selected>-- Select Device --</option>
                            @foreach($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                            @endforeach
                        </select>

                        @error('device')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="type">Type</label>
                        <select name="type" id="type" class="form-control">
                            <option disabled selected>-- Select Type --</option>
                            <option value="all">All</option>
                            <option value="curr">Current Active</option>
                        </select>

                        @error('from')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="from">From</label>
                        <input type="date" name="from" id="from" class="form-control">

                        @error('from')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="to">To</label>
                        <input type="date" name="to" id="to" class="form-control">

                        @error('from')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop