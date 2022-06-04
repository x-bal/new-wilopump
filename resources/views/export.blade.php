<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Date</th>
            @foreach($device->digitalInputs as $dig)
            <th>{{ $dig->name }}</th>
            @endforeach
            @foreach($device->modbuses as $modbus)
            <th>{{ $modbus->name }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($histories as $history)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $history->created_at }}</td>
            @foreach(App\Models\History::where('time', $history->time)->get() as $his)
            @if($his->digital_input_id != 0)
            <td>
                {{ $his->val == 1 ? App\Models\DigitalInput::where('id', $his->digital_input_id)->first()->yes : App\Models\DigitalInput::where('id', $his->digital_input_id)->first()->no }}
            </td>
            @endif
            @endforeach
            @foreach(App\Models\History::where('time', $history->time)->get() as $hs)
            @if($hs->modbus_id != 0)
            <td>
                {{ $hs->val }}
            </td>
            @endif
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>