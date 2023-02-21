<?php

namespace App\Http\Controllers\v1;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /*
        Listing of Roles and Permissions Data
        Showing Data with json response
    */
    public function list(Request $request)
    {
        $request->validate([
            'name'          => 'required|string',
            'sortOrder'     => 'required|in:asc,desc',
            'sortField'     => 'required|string',
            'perpage'       => 'required|integer',
            'currentPage'   => 'required|integer'
        ]);
        $roles = Role::query()->where("name", "LIKE", "%{$request->name}%");
        if ($request->sortField && $request->sortOrder) {
            $roles = $roles->orderBy($request->sortField, $request->sortOrder);
        } else {
            $roles = $roles->orderBy('id', 'DESC');
        }
        $perpage = $request->perpage;
        $currentPage = $request->currentPage;
        $roles = $roles->skip($perpage * ($currentPage - 1))->take($perpage);
        return response()->json([
            'success' => true,
            'message' => "Role View",
            'data'    => $roles->get()
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'                          => 'required',
            'description'                   => 'required',
            'permissions.*'                 => 'required|array',
            'permissions.*.permission_id'   => 'required|string'
        ]);
        $role = Role::create($request->only('name', 'description'));
        $role->permissions()->attach($request->permissions);
        return response()->json([
            'success' => true,
            'message' => "Created Successfully",
            'data'    => $role->load('permissions')
        ]);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $role
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'name'                          => 'required',
            'description'                   => 'required',
            'permissions.*'                 => 'required|array',
            'permissions.*.permission_id'   => 'required|string'
        ]);
        $role->update($request->only('name', 'description'));
        $role->permissions()->sync($request->permissions);
        return response()->json([
            'success' => true,
            'message' => "Updated Successfully",
        ]);
    }

    /*
        Soft Deletion of Roles Data
    */
    public function softDelete(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'softDelete'   => 'required|bool',
        ]);
        if ($request->softDelete) {
            if ($role->permissions()->count() > 0) {
                $role->permissions()->detach();
            }
            $role->delete();
        } else {
            if ($role->permissions()->count() > 0) {
                $role->permissions()->detach();
            }
            $role->forceDelete();
        }
        return response()->json([
            'success' => true,
            'message' => "Soft Deleted Successfully",
        ]);
    }

    public function restore($id)
    {
        Role::withTrashed()->find($id)->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored Data Successfully",
        ]);
    }

    public function restoreAll()
    {
        Role::onlyTrashed()->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored All Data Successfully",
        ]);
    }
}
