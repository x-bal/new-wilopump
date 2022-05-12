<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

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
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Device $device)
    {
        return view('device.show', compact('device'));
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
            'satuan' => 'required|string',
            'type' => 'required|string',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $device->update($attr);

            DB::commit();

            return redirect()->route('device.index')->with('success', 'Device sucessfully updated');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Device $device)
    {
        //
    }
}
