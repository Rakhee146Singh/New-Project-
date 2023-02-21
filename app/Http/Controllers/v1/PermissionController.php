<?php

namespace App\Http\Controllers\v1;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ModulePermission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /*
        Listing of Permission and Modules Data
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
        $permissions = Permission::query()->where("name", "LIKE", "%{$request->name}%");
        if ($request->sortField && $request->sortOrder) {
            $permissions = $permissions->orderBy($request->sortField, $request->sortOrder);
        } else {
            $permissions = $permissions->orderBy('id', 'DESC');
        }
        $perpage = $request->perpage;
        $currentPage = $request->currentPage;
        $permissions = $permissions->skip($perpage * ($currentPage - 1))->take($perpage);
        return response()->json([
            'success' => true,
            'message' => "Permission View",
            'data'    => $permissions->get()
        ]);
    }

    /*
        Create new Permission with multiple module selection
        Validation of data and reponse with success message
    */
    public function create(Request $request)
    {
        $request->validate([
            'name'                      => 'required',
            'description'               => 'required',
            'modules.*'                 => 'required|array',
            'modules.*.module_id'       => 'required',
            'modules.*.add_access'      => 'required|bool',
            'modules.*.edit_access'     => 'required|bool',
            'modules.*.delete_access'   => 'required|bool',
            'modules.*.view_access'     => 'required|bool'
        ]);
        $permission = Permission::create($request->only('name', 'description'));
        $permission->modules()->createMany($request->modules);
        return response()->json([
            'success' => true,
            'message' => "Created Successfully",
            'data'    => $permission->load('modules')
        ]);
    }

    /*
        Showing Permissions Data
        fetched data to be edit
    */
    public function show($id)
    {
        $permission = Permission::with('modules')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }

    /*
        Updating Permissions and Modules Data
        Validation of data and reponse with updated message
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                      => 'required',
            'description'               => 'required',
            'modules.*'                 => 'required|array',
            'modules.*.module_id'       => 'required',
            'modules.*.add_access'      => 'required|bool',
            'modules.*.edit_access'     => 'required|bool',
            'modules.*.delete_access'   => 'required|bool',
            'modules.*.view_access'     => 'required|bool'
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update($request->only('name', 'description'));
        foreach ($request['modules'] as $module) {
            ModulePermission::updateOrCreate(
                [
                    'permission_id' => $permission->id,
                    'module_id'     => $module['module_id']
                ],
                [
                    'add_access'    => $module['add_access'],
                    'edit_access'   => $module['edit_access'],
                    'delete_access' => $module['delete_access'],
                    'view_access'   => $module['view_access'],
                ]
            );
        }
        return response()->json([
            'success' => true,
            'message' => "Updated Successfully",
        ]);
    }

    /*
        Soft and Hard Deletion of Permissions Data
    */
    public function softDelete(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $request->validate([
            'softDelete'   => 'required|bool',
        ]);
        if ($request->softDelete) {
            if ($permission->modules()->count() > 0) {
                $permission->modules()->delete();
            }
            $permission->delete();
        } else {
            if ($permission->modules()->count() > 0) {
                $permission->modules()->forceDelete();
            }
            $permission->forceDelete();
        }
        return response()->json([
            'success' => true,
            'message' => "Soft Deleted Successfully",
        ]);
    }

    public function restore($id)
    {
        Permission::whereId($id)->withTrashed()->restore();
        ModulePermission::where('permission_id', $id)->withTrashed()->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored Data Successfully",
        ]);
    }

    public function restoreAll()
    {
        Permission::onlyTrashed()->restore();
        ModulePermission::onlyTrashed()->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored All Data Successfully",
        ]);
    }
}
