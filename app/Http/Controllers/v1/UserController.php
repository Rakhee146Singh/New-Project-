<?php

namespace App\Http\Controllers\v1;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * API of listing Users data.
     *
     * @return $users
     */
    public function list(Request $request)
    {
        //validation for searching,sorting fields
        $request->validate([
            'first_name'    => 'required|string',
            'sortOrder'     => 'required|in:asc,desc',
            'sortField'     => 'required|string',
            'perpage'       => 'required|integer',
            'currentPage'   => 'required|integer'
        ]);
        //pass query for permission with searching,sorting and filters
        $users = User::query();
        if ($request->sortField && $request->sortOrder) {
            $users = $users->orderBy($request->sortField, $request->sortOrder);
        } else {
            $users = $users->orderBy('id', 'DESC');
        };

        if (isset($request->name)) {
            $users->where("first_name", "LIKE", "%{$request->first_name}%");
        }
        //pagination code
        $perPage = $request->perpage;
        $currentPage = $request->currentPage;
        $users = $users->skip($perPage * ($currentPage - 1))->take($perPage);

        //response in json with success message
        return response()->json([
            'success' => true,
            'message' => "User View",
            'data'    => $users->get()
        ]);
    }

    /**
     * API of new create User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response $user
     */
    public function create(Request $request)
    {
        $request->validate([
            'first_name'             => 'required',
            'last_name'              => 'required',
            'email'                  => 'required',
            'password'               => 'required|min:6',
            'password_confirmation'  => 'required|same:password',
            'code'                   => 'required',
            'type'                   => 'required',
            'roles.*'                => 'required|array',
            'roles.*.role_id'        => 'required|string'
        ]);
        $request['password'] = Hash::make($request->password);
        $user = User::create($request->only('first_name', 'last_name', 'email', 'password', 'code', 'type'));

        //enter data in pivot table
        $user->roles()->attach($request->roles);
        return response()->json([
            'success' => true,
            'message' => "Created Successfully",
            'data'    => $user->load('roles')
        ]);
    }

    /**
     * API to get User with $id.
     *
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response $user
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * API of Update User Data.
     *
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * API of Delete User data.
     *
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response
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

    /**
     * API of restore User Data.
     *
     * @param  \App\User  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        User::withTrashed()->find($id)->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored Data Successfully",
        ]);
    }
}
