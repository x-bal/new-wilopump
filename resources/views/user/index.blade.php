@extends('layouts.master', ['title' => 'Data User'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-3 lh-sm">Data User</h2>

        <div id="tableExample2" data-list='{"valueNames":["no","image","name","email","level", "action"],"page":10,"pagination":true}'>

            <a href="{{ route('user.create') }}" class="btn btn-sm btn-success mb-3">Add User</a>

            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort" data-sort="no">No</th>
                            <th class="sort" data-sort="image">Image</th>
                            <th class="sort" data-sort="name">Name</th>
                            <th class="sort" data-sort="email">Email</th>
                            <th class="sort" data-sort="level">Level</th>
                            <th class="sort" data-sort="action">Action</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach($users as $user)
                        <tr>
                            <td class="no">{{ $loop->iteration }}</td>
                            <td class="image"><img src="{{ asset('storage/' . $user->image) }}" alt="" width="70"></td>
                            <td class="name">{{ $user->name }}</td>
                            <td class="email">{{ $user->email }}</td>
                            <td class="age">{{ $user->level }}</td>
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
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="pagination mb-0"></ul>
                <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
            </div>
        </div>
    </div>
</div>
@stop