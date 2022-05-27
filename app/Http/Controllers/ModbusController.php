<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Merge;
use App\Models\Modbus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModbusController extends Controller
{
    public function update()
    {
        request()->validate([
            'id' => 'required',
            'field' => 'required',
            'val' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $modbus = Modbus::findOrFail(request('id'));
            $modbus->update([
                request('field') => request('val')
            ]);

            DB::commit();

            if (request('field') == 'name') {
                $message = 'Modbus name successfully updated';
            }

            if (request('field') == 'satuan') {
                $message = 'Modbus satuan successfully updated';
            }

            if (request('field') == 'is_used' && request('val') == 1) {
                $message = 'Modbus successfully activated';
            }

            if (request('field') == 'is_used' && request('val') == 0) {
                $message = 'Modbus successfully deactivated';
            }

            return response()->json([
                'status' => 'success',
                'message' => $message
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function merge(Request $request)
    {
        $attr = $request->validate(
            [
                'name' => 'required',
                'convert' => 'required',
                'modbus_id' => 'required',
            ],
            [
                'modbus_id.required' => 'Select Modbus'
            ]
        );

        try {
            DB::beginTransaction();
            if (count($request->modbus_id) <= 2 && count($request->modbus_id) != 0) {
                $modbusOne = Modbus::findOrFail($request->modbus_id[0]);
                $modbusTwo = Modbus::findOrFail($request->modbus_id[1]);

                if ($modbusOne->merge == 0 && $modbusTwo->merge == 0) {
                    $decOne = dechex($modbusOne->val);
                    $decTwo = dechex($modbusTwo->val);

                    $val = $this->endian($request->convert, $decOne, $decTwo);

                    $merge = Merge::create([
                        'device_id' => $modbusOne->device_id,
                        'name' => $request->name,
                        'val' => $val
                    ]);

                    $modbusOne->update([
                        'merge_id' => $merge->id
                    ]);

                    $modbusTwo->update([
                        'merge_id' => $merge->id
                    ]);

                    History::create([
                        'device_id' => $merge->device_id,
                        'ket' => 'Merge ' . $modbusOne->name . ' & ' . $modbusTwo->name,
                        'val' => $val
                    ]);

                    DB::commit();

                    return back()->with('success', 'Modbus successfully merged');
                } else {
                    return back()->with('error', "Modbus has been merged");
                }
            } else {
                return back()->with('error', "Maximum merge is 2 modbus");
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function hex2float($strHex)
    {
        $hex = sscanf($strHex, "%02x%02x%02x%02x%02x%02x%02x%02x");
        $bin = implode('', array_map('chr', $hex));
        $array = unpack("Gnum", $bin);
        return $array['num'];
    }

    public function endian($convert, $decOne, $decTwo)
    {
        $hexOne = str_split($decOne);
        $hexTwo = str_split($decTwo);

        $a = $hexOne[0] . $hexOne[1];
        $b = $hexOne[2] . $hexOne[3];
        $c = $hexTwo[0] . $hexTwo[1];
        $d = $hexTwo[2] . $hexTwo[3];

        if ($convert == 'be') {
            $hexa = $a . $b . $c . $d;

            $hexConvert = $this->hex2float($hexa);
        }

        if ($convert == 'le') {
            $hexa = $d . $c . $b . $a;

            $hexConvert = $this->hex2float($hexa);
        }

        if ($convert == 'le') {
            $hexa = $d . $c . $b . $a;

            $hexConvert = $this->hex2float($hexa);
        }

        if ($convert == 'mbe') {
            $hexa = $b . $a . $d . $c;

            $hexConvert = $this->hex2float($hexa);
        }

        if ($convert == 'mle') {
            $hexa = $c . $d . $a . $b;

            $hexConvert = $this->hex2float($hexa);
        }

        return number_format($hexConvert, 2);
    }

    public function deleteMerge(Merge $merge)
    {
        try {
            DB::beginTransaction();

            foreach ($merge->modbuses() as $modbus) {
                $modbus->update(['merge_id' => 0]);
            }

            $merge->delete();

            DB::commit();
            return back()->with('success', 'Merge successfully deleted');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
