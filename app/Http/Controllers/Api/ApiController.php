<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\SecretKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function SendDataModbus(Request $request)
    {
        if ($request->key && $request->iddev && $request->address && $request->idmodbus && $request->val && $request->used) {
            $secretkey = SecretKey::findOrFail(1);

            if ($secretkey->key == $request->key) {
                $device = Device::find($request->iddev);

                if ($device) {
                    try {
                        DB::beginTransaction();

                        $address = $request->address;
                        $idmodbus = $request->idmodbus;
                        $val = $request->val;
                        $used = $request->used;
                        $limit = count($address);

                        foreach ($device->modbuses()->limit($limit)->get() as $i => $modbus) {
                            $modbus->update([
                                'address' => $address[$i],
                                'id_modbus' => $idmodbus[$i],
                                'val' => $val[$i],
                                'is_used' => $used[$i],
                            ]);
                        }

                        DB::commit();

                        return response()->json([
                            'status' => 'success',
                            // 'modbuses' => $device->modbuses()->limit($limit)->get()
                            'address' => $address,
                            'idmodbus' => $idmodbus,
                            'val' => $val,
                        ]);
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'success',
                            'modbuses' => $th->getMessage()
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Device not found'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Secret key not matches'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong parameters'
            ]);
        }
    }

    public function sendDataDigital(Request $request)
    {
        if ($request->key && $request->iddev && $request->used) {
            $secretkey = SecretKey::findOrFail(1);

            if ($secretkey->key == $request->key) {
                $device = Device::find($request->iddev);

                if ($device) {
                    try {
                        DB::beginTransaction();
                        $used = $request->used;
                        $limit = count($used);

                        foreach ($device->digitalInputs()->limit($limit)->get() as $i => $modbus) {
                            $modbus->update([
                                'is_used' => $used[$i],
                            ]);
                        }

                        DB::commit();

                        return response()->json([
                            'status' => 'success',
                            'digitalInputs' => $device->digitalInputs()->limit($limit)->get()
                        ]);
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'success',
                            'modbuses' => $th->getMessage()
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Device not found'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Secret key not matches'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong parameters'
            ]);
        }
    }
}
