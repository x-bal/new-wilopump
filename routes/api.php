<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DigitalInputController;
use App\Http\Controllers\ModbusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route only web
Route::get('/modbus', [ModbusController::class, 'update']);
Route::get('/digital', [DigitalInputController::class, 'update']);
Route::get('/get-device', [DeviceController::class, 'get']);
Route::get('/get-device/{device:id}', [DeviceController::class, 'find']);
Route::get('/device/active', [DeviceController::class, 'active']);
Route::get('/math', [DeviceController::class, 'math']);
Route::get('/merge/change', [ModbusController::class, 'change']);
Route::get('/merge/math', [ModbusController::class, 'math']);
Route::get('/merge', [ModbusController::class, 'updateMerge']);

// Route api device
Route::post('/send-data-modbus', [ApiController::class, 'sendDataModbus']);
Route::post('/send-data-digital', [ApiController::class, 'SendDataDigital']);
