<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function list()
    {
        $role = Role::all();
        return response()->json([
            'success' => true,
            'message' => "Role View",
            'data'    => $role
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'description'   => 'required'
        ]);
        $role = Role::create($request->only('name', 'description'));
        $role->permissions()->attach($request->permissions);
        return response()->json([
            'success' => true,
            'message' => "Created Successfully",
            'data'    => $role
        ]);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $role->update($request->only('name', 'description'));
        return response()->json([
            'success' => true,
            'message' => "Updated Successfully",
        ]);
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => "Deleted Successfully",
        ]);
    }
}
