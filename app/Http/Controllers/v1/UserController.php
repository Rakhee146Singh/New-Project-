<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function list()
    {
        $user = User::all();
        return response()->json([
            'success' => true,
            'message' => "User View",
            'data'    => $user
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'first_name'             => 'required',
            'last_name'              => 'required',
            'email'                  => 'required|email',
            'password'               => 'required|min:6',
            'password_confirmation'  => 'required|same:password',
            'code'                   => 'required',
            'type'                   => 'required',
        ]);
        $request['password'] = Hash::make($request->password);
        $user = User::create($request->only('first_name', 'last_name', 'email', 'password', 'code', 'type'));
        $user->roles()->attach($request->roles);
        return response()->json([
            'success' => true,
            'message' => "Created Successfully",
            'data'    => $user
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email'     => 'required|email',
            'code'      => 'required|max:6',
            'type'      => 'required',
        ]);
        $user->update($request->only('first_name', 'last_name', 'email', 'code', 'type'));
        return response()->json([
            'success' => true,
            'message' => "Updated Successfully",
        ]);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => "Deleted Successfully",
        ]);
    }
}
