<?php

namespace App\Http\Controllers\v1;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * API of listing Roles data.
     *
     * @return $roles
     */
    public function list(Request $request)
    {
        //validation for searching,sorting fields
        $request->validate([
            'name'          => 'required|string',
            'sortOrder'     => 'required|in:asc,desc',
            'sortField'     => 'required|string',
            'perpage'       => 'required|integer',
            'currentPage'   => 'required|integer'
        ]);
        //pass query for permission with searching,sorting and filters
        $roles = Role::query();
        if ($request->sortField && $request->sortOrder) {
            $roles = $roles->orderBy($request->sortField, $request->sortOrder);
        } else {
            $roles = $roles->orderBy('id', 'DESC');
        }

        if (isset($request->name)) {
            $roles->where("name", "LIKE", "%{$request->name}%");
        }
        //pagination code
        $perPage = $request->perpage;
        $currentPage = $request->currentPage;
        $roles = $roles->skip($perPage * ($currentPage - 1))->take($perPage);

        //response in json with success message
        return response()->json([
            'success' => true,
            'message' => "Role View",
            'data'    => $roles->get()
        ]);
    }

    /**
     * API of new create Role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response $role
     */
    public function create(Request $request)
    {
        $request->validate([
            'name'                          => 'required',
            'description'                   => 'required',
            'permissions.*'                 => 'required|array',
            'permissions.*.permission_id'   => 'required|string'
        ]);
        $role = Role::create($request->only('name', 'description'));
        //enter data in pivot table
        $role->permissions()->attach($request->permissions);
        return response()->json([
            'success' => true,
            'message' => "Created Successfully",
            'data'    => $role->load('permissions')
        ]);
    }

    /**
     * API to get Role with $id.
     *
     * @param  \App\Role  $id
     * @return \Illuminate\Http\Response $role
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $role
        ]);
    }

    /**
     * API of Update Role Data.
     *
     * @param  \App\Role  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * API of Delete Module data.
     *
     * @param  \App\Role  $id
     * @return \Illuminate\Http\Response
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

    /**
     * API of restore Module Data.
     *
     * @param  \App\Role  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        Role::withTrashed()->find($id)->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored Data Successfully",
        ]);
    }
}
