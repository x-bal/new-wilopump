<?php

namespace App\Exports;

use App\Models\Device;
use App\Models\DigitalInput;
use App\Models\History;
use App\Models\Modbus;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class HistoryExport implements FromView
{
    public function view(): View
    {
        $from = request('from');
        $to = Carbon::parse(request('to'))->addDay(1)->format('Y-m-d');
        $device = Device::find(request('device'));

        $digital = DigitalInput::where('device_id', $device->id)->where('is_used', 1)->get();
        $modbus = Modbus::where('device_id', $device->id)->where('is_used', 1)->get();

        if ($from == '' && $to == '') {
            $history = History::where('device_id', $device->id)->groupBy('time')->get();
        } else {
            $history = History::where('device_id', $device->id)->whereBetween('created_at', [$from, $to])->groupBy('time')->get();
        }


        return view('export', [
            'device' => $device,
            'history' => $history,
            'digital' => $digital,
            'modbus' => $modbus,
        ]);
    }
}
