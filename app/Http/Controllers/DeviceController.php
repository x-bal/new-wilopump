<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DigitalInput;
use App\Models\History;
use App\Models\Merge;
use App\Models\Modbus;
use App\Models\SecretKey;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::get();

        return view('device.index', compact('devices'));
    }

    public function create()
    {
        $device = new Device();
        $action = route('device.store');
        $act = 'create';

        return view('device.form', compact('device', 'action', 'act'));
    }

    public function store(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'power' => 'required|string',
            'flow' => 'required|string',
            'head' => 'required|string',
            'modbus' => 'required|string',
            'digital' => 'required|string',
            'lat' => 'required|string',
            'long' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg,png',
        ]);

        try {
            DB::beginTransaction();

            $image = $request->file('image');
            $attr['image'] = $image->storeAs('images/device', 'dev' . date('Ymd') . rand(1000, 9999) . '.' . $image->extension());

            $device = Device::create($attr);
            $modbuses = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];
            $digital = [1, 2, 3, 4, 5, 6, 7,];

            foreach ($modbuses as $modbus) {
                $device->modbuses()->create([
                    'name' => 'Modbus ' . $modbus,
                ]);
            }

            foreach ($digital as $dig) {
                $device->digitalInputs()->create([
                    'name' => 'Digital Input ' . $dig,
                    'digital_input' => $dig
                ]);
            }

            DB::commit();

            return redirect()->route('device.index')->with('success', 'Device sucessfully created');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Device $device)
    {
        $apikey = SecretKey::findOrFail(2)->key;

        return view('device.show', compact('device', 'apikey'));
    }

    public function edit(Device $device)
    {
        $action = route('device.update', $device->id);
        $act = 'edit';

        return view('device.form', compact('device', 'action', 'act'));
    }

    public function update(Request $request, Device $device)
    {
        $attr = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'power' => 'required|string',
            'flow' => 'required|string',
            'head' => 'required|string',
            'modbus' => 'required|string',
            'digital' => 'required|string',
            'lat' => 'required|string',
            'long' => 'required|string',
            'image' => 'mimes:jpg,jpeg,png',
        ]);

        try {
            DB::beginTransaction();

            if ($request->file('image')) {
                Storage::delete($device->image);
                $image = $request->file('image');
                $attr['image'] = $image->storeAs('images/device', 'dev' . date('Ymd') . rand(1000, 9999) . '.' . $image->extension());
            } else {
                $attr['image'] = $device->image;
            }

            $device->update($attr);

            DB::commit();

            return redirect()->route('device.index')->with('success', 'Device sucessfully updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Device $device)
    {
        //
    }

    public function get()
    {
        if (auth()->user()->level == 'Viewer') {
            $user = User::find(auth()->user()->id);
            $first = $user->devices()->first();
            if ($first) {
                $devices = $user->devices;
            } else {
                $devices = '';
            }
        } else {
            $first = Device::where('is_active', 1)->first();
            if ($first) {
                $devices = Device::where('is_active', 1)->get();
            } else {
                $devices = '';
            }
        }

        return response()->json([
            'devices' => $devices
        ]);
    }

    public function find(Device $device)
    {
        $modbus = $device->modbuses()->where('is_used', 1)->get();
        $digital = $device->digitalInputs()->where('is_used', 1)->get();
        $image = asset('/storage/' . $device->image);
        $history = $device->histories()->latest()->first();
        $merge = Merge::where('device_id', $device->id)->get();

        return response()->json([
            'device' => $device,
            'modbus' => $modbus,
            'digital' => $digital,
            'merge' => $merge,
            'image' => $image,
            'history' => $history ? Carbon::parse($history->created_at)->format('d/m/Y H:i:s') : '-',
        ]);
    }

    public function math(Request $request)
    {
        try {
            DB::beginTransaction();
            $modbus = Modbus::find($request->id);

            $modbus->update([
                'math' => '',
            ]);

            $modbus->update([
                'math' => $request->math,
                'after' => $request->after,
            ]);


            History::create([
                'device_id' => $modbus->device->id,
                'val' => $request->after,
                'ket' => 'Math ' . $modbus->name
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Value modbus successfully updated'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function active()
    {
        try {
            DB::beginTransaction();

            $device = Device::find(request('id'));
            $device->update([
                'is_active' => request('active')
            ]);

            if (request('active') == 1) {
                $message = 'Device successfully activated';
            } else {
                $message = 'Device successfully non activated';
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function history(Device $device)
    {
        $modbus = History::with('modbus')->where('device_id', $device->id)->where('modbus_id', '!=', 0)->latest()->get();
        $digital = History::with('digital')->where('device_id', $device->id)->where('digital_input_id', '!=', 0)->latest()->get();

        return response()->json([
            'device' => $device,
            'modbus' => $modbus,
            'digital' => $digital,
        ]);
    }

    public function historyModbus(Device $device)
    {
        if (request('from') != '' && request('to') != '') {
            $active = Modbus::where('device_id', $device->id)->where('is_showed', 1)->whereHas('histories', function ($q) {
                $q->whereBetween('created_at', [request('from'), Carbon::parse(request('to'))->addDay(1)->format('Y-m-d')]);
            })->with('histories', function ($q) {
                $q->whereBetween('created_at', [request('from'), Carbon::parse(request('to'))->addDay(1)->format('Y-m-d')]);
            })->latest()->get();
        } else {
            $active = Modbus::where('device_id', $device->id)->where('is_showed', 1)->with('histories', function ($q) {
                $q->latest()->limit(10);
            })->whereHas('histories', function ($q) {
                $q->latest()->limit(10);
            })->get();

            $digital = DigitalInput::where('device_id', $device->id)->where('is_used', 1)->get();
            $modbus = Modbus::where('device_id', $device->id)->where('is_used', 1)->get();
            $history = History::where('device_id', $device->id)->groupBy('time')->limit(10)->get();
        }

        return response()->json([
            'active' => $active,
            'history' => $history,
            'digital' => $digital,
            'modbus' => $modbus,
        ]);
    }

    public function grafik(Device $device)
    {
        $apikey = SecretKey::findOrFail(2)->key;
        $digital = DigitalInput::where('device_id', $device->id)->where('is_used', 1)->get();
        $modbus = Modbus::where('device_id', $device->id)->where('is_used', 1)->get();
        $history = History::where('device_id', $device->id)->groupBy('time')->limit(10)->get();

        return view('device.grafik', compact('device', 'apikey', 'modbus', 'digital', 'history',));
    }
}
