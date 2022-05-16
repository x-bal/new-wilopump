@extends('layouts.master', ['title' => 'History'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">History Device</h2>
    </div>

    <div class="col-md-12">
        <div id="tableModbus" data-list='{"valueNames":["no","device","desc","time"],"page":10,"pagination":true}'>

            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort text-center" data-sort="no">No</th>
                            <th class="sort" data-sort="time">Time</th>
                            <th class="sort" data-sort="device">Device</th>
                            <th class="sort" data-sort="desc">Desc</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach($histories as $history)
                        <tr>
                            <td class="no text-center">{{ $loop->iteration }}</td>
                            <td class="time">{{ Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="device">{{ $history->device->name }}</td>
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
@stop