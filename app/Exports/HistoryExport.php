<?php

namespace App\Exports;

use App\Models\Device;
use App\Models\History;
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

        $histories = History::where('device_id', $device->id)->whereBetween('created_at', [$from, $to])->groupBy('time')->get();


        return view('export', [
            'device' => $device,
            'histories' => $histories,
        ]);
    }
}
