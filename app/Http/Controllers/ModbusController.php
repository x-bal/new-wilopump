<?php

namespace App\Http\Controllers;

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
}
