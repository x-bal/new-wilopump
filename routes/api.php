<?php

use App\Http\Controllers\Api\ApiController;
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

// Route untuk di web saja
Route::get('/modbus', [ModbusController::class, 'update']);
Route::get('/digital', [DigitalInputController::class, 'update']);

// Route untuk device
Route::post('/send-data-modbus', [ApiController::class, 'sendDataModbus']);
Route::post('/send-data-digital', [ApiController::class, 'SendDataDigital']);
