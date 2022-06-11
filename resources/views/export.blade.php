<table class="table table-bordered table-striped fs--1 mb-0">
    <thead class="bg-200 text-900 bg-success text-white">
        <tr>
            <th class="sort" data-sort="no">No</th>
            <th class="sort" data-sort="date">Date</th>
            @foreach($digital as $dig)
            <th class="sort" data-sort="id">{{ $dig->name }}</th>
            @endforeach
            @foreach($modbus as $mod)
            <th class="sort" data-sort="id">{{ $mod->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody class="list">
        @foreach($history as $hd)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ Carbon\Carbon::parse($hd->created_at)->format('d/m/Y H:i:s') }}</td>
            @foreach(App\Models\History::where('time', $hd->time)->whereHas('digital', function($q){
            $q->where('is_used', 1);
            })->get() as $dig)
            <td>
                {{ $dig->val == 1 ? $dig->digital->yes : $dig->digital->no }}
            </td>
            @endforeach
            @foreach(App\Models\History::where('time', $hd->time)->whereHas('modbus', function($q){
            $q->where('is_used', 1);
            })->get() as $mod)
            <td>{{ $mod->val }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>