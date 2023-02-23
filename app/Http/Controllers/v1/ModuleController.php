<?php

namespace App\Http\Controllers\v1;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ModuleController extends Controller
{
    /**
     * API of listing Modules data.
     *
     * @return $modules
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
        $modules = Module::query();
        if ($request->sortField && $request->sortOrder) {
            $modules = $modules->orderBy($request->sortField, $request->sortOrder);
        } else {
            $modules = $modules->orderBy('id', 'DESC');
        };

        if (isset($request->name)) {
            $modules->where("name", "LIKE", "%{$request->name}%");
        }
        //pagination code
        $perPage = $request->perpage;
        $currentPage = $request->currentPage;
        $modules = $modules->skip($perPage * ($currentPage - 1))->take($perPage);

        //response in json with success message
        return response()->json([
            'success'   => true,
            'message'   => "Module List",
            'data'      => $modules->get()
        ]);
    }

    /**
     * API of new create Module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response $module
     */
    public function create(Request $request)
    {
        $request->validate([
            'module_code'    => 'required|unique:modules,module_code',
            'name'           => 'required',
            'is_in_menu'     => 'required'
        ]);
        $module = Module::create($request->only('name', 'module_code', 'is_in_menu'));
        return response()->json([
            'success'   => true,
            'message'   => "Created Successfully",
            'data'      => $module
        ]);
    }

    /**
     * API to get Module with $id.
     *
     * @param  \App\Module  $id
     * @return \Illuminate\Http\Response $module
     */
    public function show($id)
    {
        $module = Module::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $module
        ]);
    }

    /**
     * API of Update Module Data.
     *
     * @param  \App\Module  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);
        $request->validate([
            'module_code'   => 'required|max:6',
            'name'          => 'required',
            'is_in_menu'    => 'required'
        ]);
        $module->update($request->only('name', 'module_code', 'is_in_menu'));
        return response()->json([
            'success' => true,
            'message' => "Updated Successfully",
        ]);
    }

    /**
     * API of Delete Module data.
     *
     * @param  \App\Module  $id
     * @return \Illuminate\Http\Response
     */
    public function softDelete($id, Request $request)
    {
        $request->validate([
            'softDelete'   => 'required|bool',
        ]);
        if ($request->softDelete) {
            Module::findOrFail($id)->delete();
        } else {
            Module::findOrFail($id)->forceDelete();
        }
        return response()->json([
            'success' => true,
            'message' => "Module Deleted Successfully",
        ]);
    }

    /**
     * API of restore Module Data.
     *
     * @param  \App\Module  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        Module::withTrashed()->find($id)->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored Data Successfully",
        ]);
    }
}
