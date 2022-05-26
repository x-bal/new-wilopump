<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use App\Models\SecretKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $apikey = SecretKey::findOrFail(2)->key;
        $first = Device::where('is_active', 1)->first();
        if ($first) {
            $devices = Device::where('is_active', 1)->where('id', '!=', $first->id)->get();
        } else {
            $devices = '';
        }

        return view('dashboard.index', compact('apikey', 'first', 'devices'));
    }

    public function slider()
    {
        $apikey = SecretKey::findOrFail(2)->key;
        $delay = intval(SecretKey::findOrFail(3)->key * 1000);
        $first = Device::where('is_active', 1)->first();
        $devices = Device::where('is_active', 1)->where('id', '!=', $first->id)->get();
        return view('dashboard.slider', compact('apikey', 'first', 'devices', 'delay'));
    }

    public function setting()
    {
        $apikey = SecretKey::findOrFail(2);
        $delay = SecretKey::findOrFail(3);

        return view('dashboard.setting', compact('apikey', 'delay'));
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

        return view('dashboard.history', compact('histories'));
    }
}
