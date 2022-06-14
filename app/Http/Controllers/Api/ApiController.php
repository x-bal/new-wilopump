<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\History;
use App\Models\SecretKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function SendDataModbus(Request $request)
    {
        if ($request->key && $request->iddev && $request->addr && $request->idm && $request->val && $request->used) {
            $secretkey = SecretKey::findOrFail(1);

            if ($secretkey->key == $request->key) {
                $device = Device::find($request->iddev);

                if ($device) {
                    if ($device->is_active == 1) {
                        try {
                            DB::beginTransaction();

                            $address = $request->addr;
                            $idmodbus = $request->idm;
                            $val = $request->val;
                            $used = $request->used;
                            $limit = count($address);

                            foreach ($device->modbuses()->limit($limit)->get() as $i => $modbus) {
                                if ($modbus->math != NULL) {
                                    $math = explode(',', $modbus->math);

                                    if ($math[0] == 'x') {
                                        $after = $val[$i] * floatval($math[1]);
                                    }

                                    if ($math[0] == ':') {
                                        $after = $val[$i] / floatval($math[1]);
                                    }

                                    if ($math[0] == '+') {
                                        $after = $val[$i] + floatval($math[1]);
                                    }

                                    if ($math[0] == '-') {
                                        $after = $val[$i] - floatval($math[1]);
                                    }

                                    if ($math[0] == '&') {
                                        $rumus = explode('&', $math[1]);
                                        $after = ((($val[$i] / floatval($rumus[2])) - 4) / 16) * (floatval($rumus[0]) - floatval($rumus[1])) + floatval($rumus[1]);
                                    }
                                }

                                $modbus->update([
                                    'address' => $address[$i],
                                    'id_modbus' => $idmodbus[$i],
                                    'val' => $val[$i],
                                    'is_used' => $used[$i],
                                    'math' => $modbus->after == NULL ? 'x,1' : $modbus->math,
                                    'after' => $modbus->after == NULL || $modbus->after == 0 ? $val[$i] * 1 : $after,
                                ]);

                                History::create([
                                    'device_id' => $device->id,
                                    'modbus_id' => $modbus->id,
                                    'ket' => 'Insert Data ' . $modbus->name,
                                    'val' => $after,
                                    'time' => date('Y-m-d H:i')
                                ]);
                            }

                            foreach ($device->merges as $i => $merge) {
                                $modbuses[$i] = [];
                                foreach ($merge->modbuses as $mod) {
                                    array_push($modbuses[$i], $mod->val);
                                }

                                $valMerge = $this->endian($merge->type, dechex($modbuses[$i][0]), dechex($modbuses[$i][1]));

                                if ($merge->math != NULL) {
                                    $mathMerge = explode(',', $merge->math);

                                    if ($mathMerge[0] == 'x') {
                                        $afterMerge = $valMerge * floatval($mathMerge[1]);
                                    }

                                    if ($mathMerge[0] == ':') {
                                        $afterMerge = $valMerge / floatval($mathMerge[1]);
                                    }

                                    if ($mathMerge[0] == '+') {
                                        $afterMerge = $valMerge + floatval($mathMerge[1]);
                                    }

                                    if ($mathMerge[0] == '-') {
                                        $afterMerge = $valMerge - floatval($mathMerge[1]);
                                    }

                                    if ($mathMerge[0] == '&') {
                                        $rumusMerge = explode('&', $mathMerge[1]);
                                        $afterMerge = ((($valMerge / floatval($rumusMerge[2])) - 4) / 16) * (floatval($rumusMerge[0]) - floatval($rumusMerge[1])) + floatval($rumusMerge[1]);
                                    }
                                }

                                $merge->update([
                                    'val' => $valMerge,
                                    'math' => $merge->after == NULL ? 'x,1' : $merge->math,
                                    'after' => $merge->after == NULL || $merge->after == 0 ? $valMerge * 1 : $afterMerge
                                ]);
                            }

                            DB::commit();

                            return response()->json([
                                'status' => 'success',
                                'modbus' => $device->modbuses()->limit($limit)->get(),
                            ]);
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            return response()->json([
                                'status' => 'failed',
                                'modbuses' => $th->getMessage()
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'modbuses' => 'Device is not activated'
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
        if ($request->key && $request->iddev && $request->used && $request->val) {
            $secretkey = SecretKey::findOrFail(1);

            if ($secretkey->key == $request->key) {
                $device = Device::find($request->iddev);

                if ($device) {
                    if ($device->is_active == 1) {
                        try {
                            DB::beginTransaction();
                            $used = $request->used;
                            $value = $request->val;
                            $limit = count($used);

                            foreach ($device->digitalInputs()->limit($limit)->get() as $i => $digital) {
                                $digital->update([
                                    'is_used' => $used[$i],
                                    'val' => $value[$i],
                                ]);

                                History::create([
                                    'device_id' => $device->id,
                                    'digital_input_id' => $digital->id,
                                    'ket' => 'Insert Data ' . $digital->name,
                                    'val' => $value[$i],
                                    'time' => date('Y-m-d H:i')
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
                                'status' => 'error',
                                'message' => $th->getMessage()
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Device is not activated'
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

    public function hex2float($strHex)
    {
        $hex = sscanf($strHex, "%02x%02x%02x%02x%02x%02x%02x%02x");
        $bin = implode('', array_map('chr', $hex));
        $array = unpack("Gnum", $bin);
        return $array['num'];
    }

    public function endian($convert, $decOne, $decTwo)
    {
        $lengthOne = strlen($decOne);
        $diffOne = 4 - $lengthOne;
        $lengthTwo = strlen($decTwo);
        $diffTwo = 4 - $lengthTwo;
        $addOne = '';
        $addTwo = '';


        if ($diffOne > 0) {
            for ($i = 1; $i < $diffOne; $i++) {
                $addOne .= 0;
            }
        }

        if ($diffTwo > 0) {
            for ($i = 1; $i < $diffTwo; $i++) {
                $addTwo .= 0;
            }
        }

        $decOne = $addOne . $decOne;
        $decTwo = $addTwo . $decTwo;

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

        if ($convert == 'mbe') {
            $hexa = $b . $a . $d . $c;

            $hexConvert = $this->hex2float($hexa);
        }

        if ($convert == 'mle') {
            $hexa = $c . $d . $a . $b;

            $hexConvert = $this->hex2float($hexa);
        }

        return $hexConvert;
    }
}
