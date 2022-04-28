<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        $user = new User();
        $action = route('user.store');
        $act = 'create';

        return view('user.form', compact('user', 'action', 'act'));
    }

    public function store(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string',
            'level' => 'required|string',
            'image' => 'required|mimes:jpg,jpeg,png',
        ]);

        try {
            DB::beginTransaction();

            $image = $request->file('image');
            $attr['image'] = $image->storeAs('images/user', Str::slug($request->name) . '-' . rand(1000, 9999) . '.' . $image->extension());

            $attr['password'] = bcrypt($request->password);

            User::create($attr);

            DB::commit();

            return redirect()->route('user.index')->with('success', 'User successfully created');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        $action = route('user.update', $user->id);
        $act = 'edit';

        return view('user.form', compact('user', 'action', 'act'));
    }

    public function update(Request $request, User $user)
    {
        $attr = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email,' . $user->id,
            'level' => 'required|string',
            'image' => 'mimes:jpg,jpeg,png',
        ]);

        try {
            DB::beginTransaction();

            if ($request->file('image')) {
                Storage::delete($user->image);
                $image = $request->file('image');
                $attr['image'] = $image->storeAs('images/user', Str::slug($request->name) . '-' . rand(1000, 9999) . '.' . $image->extension());
            } else {
                $attr['image'] = $user->image;
            }

            if ($request->password) {
                $attr['password'] = bcrypt($request->password);
            } else {
                $attr['password'] = $user->password;
            }

            $user->update($attr);

            DB::commit();

            return redirect()->route('user.index')->with('success', 'User successfully updated');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            Storage::delete($user->image);
            $user->delete();

            DB::commit();

            return redirect()->route('user.index')->with('success', 'User successfully deleted');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
