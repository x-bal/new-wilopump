<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ModbusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/slider', [DashboardController::class, 'slider'])->name('slider');

    Route::get('/setting', [DashboardController::class, 'setting'])->name('setting')->middleware('admin');
    Route::post('/setting/{secret_key:id}', [DashboardController::class, 'updateSetting'])->name('setting.update')->middleware('admin');
    Route::get('/access-viewer', [DashboardController::class, 'access'])->name('access.viewer')->middleware('admin');
    Route::get('/access-create', [DashboardController::class, 'createAccess'])->name('access.create')->middleware('admin');
    Route::post('/access-store', [DashboardController::class, 'storeAccess'])->name('access.store')->middleware('admin');
    Route::get('/access-edit/{id}', [DashboardController::class, 'editAccess'])->name('access.edit')->middleware('admin');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

    Route::get('/chart', [DashboardController::class, 'chart'])->name('chart');
    Route::get('/view-data', [DashboardController::class, 'grafik'])->name('grafik');

    Route::get('/history', [DashboardController::class, 'history'])->name('history');
    Route::get('/export', [DashboardController::class, 'export'])->name('export');

    Route::resource('user', UserController::class)->middleware('admin');
    Route::get('/device/{device:id}/grafik', [DeviceController::class, 'grafik'])->name('device.grafik');
    Route::get('/device/{device:id}/reset', [DeviceController::class, 'reset'])->name('device.reset');
    Route::resource('device', DeviceController::class);

    Route::post('/modbus/merge', [ModbusController::class, 'merge'])->name('modbus.merge')->middleware('admin');
    Route::post('/merge/{merge:id}', [ModbusController::class, 'deleteMerge'])->name('merge.delete')->middleware('admin');

    Route::get('/get-device', [DeviceController::class, 'get']);
});
