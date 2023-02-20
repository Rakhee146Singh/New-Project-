<?php

namespace App\Http\Controllers\v1;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
        Listing of Users and Roles Data
        Showing Data with json response
    */
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
            'roles.*'               => 'required|array',
            'roles.*.role_id'        => 'required|string'
        ]);
        $request['password'] = Hash::make($request->password);
        $user = User::create($request->only('first_name', 'last_name', 'email', 'password', 'code', 'type'));
        $user->roles()->attach($request->roles);
        return response()->json([
            'success' => true,
            'message' => "Created Successfully",
            'data'    => $user->load('roles')
        ]);
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'first_name'         => 'required|alpha',
            'last_name'          => 'required|alpha',
            'email'              => 'required|email',
            'code'               => 'required|max:6',
            'type'               => 'required',
            'roles.*'            => 'required|array',
            'roles.*.role_id'    => 'required|string'
        ]);
        $user->update($request->only('first_name', 'last_name', 'email', 'code', 'type'));
        $user->roles()->sync($request->roles);
        return response()->json([
            'success' => true,
            'message' => "Updated Successfully",
        ]);
    }

    // public function delete($id)
    // {
    //     $user = User::findOrFail($id);
    //     if ($user->roles()->count() > 0) {
    //         $user->roles()->detach();
    //     }
    //     $user->delete();
    //     return response()->json([
    //         'success' => true,
    //         'message' => "Deleted Successfully",
    //     ]);
    // }

    /*
        Soft and Hard Deletion of Users Data
    */
    public function softDelete(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'softDelete'   => 'required|bool',
        ]);
        if ($request->softDelete) {
            if ($user->roles()->count() > 0) {
                $user->roles()->detach();
            }
            $user->delete();
        } else {
            if ($user->roles()->count() > 0) {
                $user->roles()->detach();
            }
            $user->forceDelete();
        }
        return response()->json([
            'success' => true,
            'message' => "Soft Deleted Successfully",
        ]);
    }
}
