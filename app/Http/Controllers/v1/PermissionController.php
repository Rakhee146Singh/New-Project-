<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function list()
    {
        $permission = Permission::all()->simplePaginate(10);
        return response()->json([
            'success' => true,
            'message' => "Permission View",
            'data'    => $permission
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'                   => 'required',
            'description'            => 'required',
            'modules.*'              => 'required|array',
            'modules.*.module_id'    => 'required',
            'modules.*.add_access'   => 'required|bool',
            'modules.*.edit_access'  => 'required|bool',
            'modules.*.delete_access' => 'required|bool',
            'modules.*.view_access'  => 'required|bool'
        ]);
        $permission = Permission::create($request->only('name', 'description'));
        $permission->modules()->createMany($request->modules);
        // $permission->roles()->attach($request->roles);
        return response()->json([
            'success' => true,
            'message' => "Created Successfully",
            'data'    => $permission->load('modules')
        ]);
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $request->validate([
            'name'                   => 'required',
            'description'            => 'required',
            // 'modules.*.'             => 'required',
            // 'modules.*.add_access'   => 'required',
            // 'modules.*.edit_access'  => 'required',
            // 'modules.*.delete_access' => 'required',
            // 'modules.*.view_access'  => 'required'
        ]);
        $permission->update($request->only('name', 'description'));
        return response()->json([
            'success' => true,
            'message' => "Updated Successfully",
        ]);
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => "Deleted Successfully",
        ]);
    }
}
