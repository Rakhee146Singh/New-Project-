<?php

namespace App\Http\Controllers\v1;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ModuleController extends Controller
{
    /*
        Listing of Modules Data
        Showing Data with json response
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
        $modules = Module::query()->where("name", "LIKE", "%{$request->name}%");
        if ($request->sortField && $request->sortOrder) {
            $modules = $modules->orderBy($request->sortField, $request->sortOrder);
        } else {
            $modules = $modules->orderBy('id', 'DESC');
        };
        //pagination code
        $perpage = $request->perpage;
        $currentPage = $request->currentPage;
        $modules = $modules->skip($perpage * ($currentPage - 1))->take($perpage);

        //response in json with success message
        return response()->json([
            'success'   => true,
            'message'   => "Module List",
            'data'      => $modules->get()
        ]);
    }

    /*
        Create new Module
        Validation of data and reponse with success message
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

    /*
        Showing Module Data of particular id
        fetched data to be edited
    */
    public function show($id)
    {
        $module = Module::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $module
        ]);
    }

    /*
        Updating Module Data
        Validation of data and reponse with updated message
    */
    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);
        $request->validate([
            'module_code'   => 'required|max:6',
            'name'          => 'required|apha',
            'is_in_menu'    => 'required'
        ]);
        $module->update($request->only('name', 'module_code', 'is_in_menu'));
        return response()->json([
            'success' => true,
            'message' => "Updated Successfully",
        ]);
    }

    /*
        Soft and Hard Deletion of Module Data
        Response in json with success message
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

    /*
        Restore of Module Data
        Response in json with success message
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
