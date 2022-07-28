<?php

namespace App\Http\Controllers;

use App\Exports\HistoryExport;
use App\Models\Device;
use App\Models\History;
use App\Models\Modbus;
use App\Models\SecretKey;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $apikey = SecretKey::findOrFail(2)->key;

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
                $devices = Device::where('is_active', 1)->where('id', '!=', $first->id)->get();
            } else {
                $devices = '';
            }
        }

        return view('dashboard.index', compact('apikey', 'first', 'devices'));
    }

    public function slider()
    {
        $apikey = SecretKey::findOrFail(2)->key;
        $delay = intval(SecretKey::findOrFail(3)->key * 1000);
        if (auth()->user()->level == 'Viewer') {
            $user = User::find(auth()->user()->id);
            $first = $user->devices()->where('is_active', 1)->first();
            if ($first) {
                $devices = $user->devices()->where('device_id', '!=', $first->id)->where('is_active', 1)->get();
            } else {
                $devices = [];
            }
        } else {
            $first = Device::where('is_active', 1)->first();
            if ($first) {
                $devices = Device::where('is_active', 1)->where('id', '!=', $first->id)->get();
            } else {
                $devices = [];
            }
        }
        return view('dashboard.slider', compact('apikey', 'first', 'devices', 'delay'));
    }

    public function setting()
    {
        $secretKey = SecretKey::findOrFail(1);
        $apikey = SecretKey::findOrFail(2);
        $delay = SecretKey::findOrFail(3);

        return view('dashboard.setting', compact('apikey', 'delay', 'secretKey'));
    }

    public function updateSetting(Request $request, SecretKey $secretKey)
    {
        $attr = $request->validate(['key' => 'required']);

        try {
            DB::beginTransaction();

            $secretKey->update($attr);

            DB::commit();

            if ($request->type == 'apikey') {
                $message  = 'Google api key successfully updated';
            }

            if ($request->type == 'delay') {
                $message  = 'Delay time slider successfully updated';
            }

            if ($request->type == 'secret') {
                $message  = 'Secret Key successfully updated';
            }

            return back()->with('success', $message);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function profile()
    {
        return view('dashboard.profile');
    }

    public function updateProfile(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email,' . auth()->user()->id,
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail(auth()->user()->id);

            if ($request->password) {
                $attr['password'] = bcrypt($request->password);
            } else {
                $attr['password'] = $user->password;
            }

            if ($request->file('image')) {
                Storage::delete($user->image);
                $image = $request->file('image');
                $attr['image'] = $image->storeAs('images/user', Str::slug($request->name) . '-' . rand(1000, 9999) . '.' . $image->extension());
            } else {
                $attr['image'] = $user->image;
            }

            $user->update($attr);

            DB::commit();

            return back()->with('success', 'Your profile successfully updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function history()
    {
        $histories = History::latest()->get();
        $devices = Device::where('is_active', 1)->get();

        return view('dashboard.history', compact('histories', 'devices'));
    }

    public function access()
    {
        $viewers = User::where('level', 'Viewer')->get();
        $devices = Device::where('is_active', 1)->get();

        return view('dashboard.access', compact('viewers', 'devices'));
    }

    public function createAccess()
    {
        $viewers = User::where('level', 'Viewer')->get();
        $devices = Device::where('is_active', 1)->get();

        return view('dashboard.create', compact('viewers', 'devices'));
    }

    public function editAccess($id)
    {
        $viewers = User::where('level', 'Viewer')->get();
        $devices = Device::where('is_active', 1)->get();
        $user = User::find($id);
        $userDevice = DB::table('device_user')->select('device_id')->where('user_id', $user->id)->get();

        return view('dashboard.edit', compact('viewers', 'devices', 'user', 'userDevice'));
    }

    public function storeAccess(Request $request)
    {
        $request->validate([
            'viewer_id' => 'required',
            'device_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $user = User::find($request->viewer_id);
            $user->devices()->sync($request->device_id);

            DB::commit();

            return redirect()->route('access.viewer')->with('success', 'Access device successfully gived');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new HistoryExport, 'History Device.xlsx');
    }

    public function chart()
    {
        if (auth()->user()->level == 'Admin') {
            $devices = Device::where('is_active', 1)->get();
        } else {
            $user = User::find(auth()->user()->id);
            $devices = $user->devices()->where('is_active', 1)->get();
        }
        $device = '';

        if (request('device')) {
            $device = Device::find(request('device'));
        }

        return view('dashboard.chart', compact('devices', 'device'));
    }

    public function grafik()
    {
        if (auth()->user()->level == 'Admin') {
            $devices = Device::where('is_active', 1)->get();
        } else {
            $user = User::find(auth()->user()->id);
            $devices = $user->devices;
        }

        return view('dashboard.grafik', compact('devices'));
    }
}
