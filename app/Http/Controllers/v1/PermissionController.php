<?php

namespace App\Http\Controllers\v1;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ModulePermission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * API of listing Permissions data.
     *
     * @return $permissions
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
        $permissions = Permission::query();
        if ($request->sortField && $request->sortOrder) {
            $permissions = $permissions->orderBy($request->sortField, $request->sortOrder);
        } else {
            $permissions = $permissions->orderBy('id', 'DESC');
        }






        if (isset($request->name)) {
            $permissions->where("name", "LIKE", "%{$request->name}%");
        }
        //pagination code
        $perPage = $request->perpage;
        $currentPage = $request->currentPage;
        $permissions = $permissions->skip($perPage * ($currentPage - 1))->take($perPage);

        //response in json with success message
        return response()->json([
            'success' => true,
            'message' => "Permission View",
            'data'    => $permissions->get()
        ]);
    }

    /**
     * API of new create Permission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response $permission
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

    /**
     * API to get Permission with $id.
     *
     * @param  \App\Permission  $id
     * @return \Illuminate\Http\Response $permission
     */
    public function show($id)
    {
        $permission = Permission::with('modules')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }

    /**
     * API of Update Permission Data.
     *
     * @param  \App\Permission  $id
     * @return \Illuminate\Http\Response
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
        $moduleIds = array_column($request->modules, 'module_id');
        //field to be updated with particular module access to be given
        $permission = Permission::findOrFail($id);
        $data = ModulePermission::where('permission_id', $permission->id)->whereNotIn('module_id', $moduleIds);
        if ($data->count() > 0) {
            $data->forceDelete();
        }
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

    /**
     * API of Delete Permission data.
     *
     * @param  \App\Permission  $id
     * @return \Illuminate\Http\Response
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
            'message' => "Deleted Successfully",
        ]);
    }

    /**
     * API of restore Permission Data.
     *
     * @param  \App\Permission  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        Permission::whereId($id)->withTrashed()->restore();
        ModulePermission::where('permission_id', $id)->withTrashed()->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored Data Successfully",
        ]);
    }
}
