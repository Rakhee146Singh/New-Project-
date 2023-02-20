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
    public function list()
    {
        $modules = Module::all();
        return response()->json([
            'success'   => true,
            'message'   => "Module List",
            'data'      => $modules
        ]);
    }

    /*
        Create new Module
        Validation of data and reponse with success message
    */
    public function create(Request $request)
    {
        $request->validate([
            'module_code'    => 'required',
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
        Showing Modules Data
        fetched data to be edit
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
        Updating Modules Data
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

    // /*
    //     Deletion of Modules Data
    // */
    // public function delete($id)
    // {
    //     Module::findOrFail($id)->forceDelete();
    //     return response()->json([
    //         'success' => true,
    //         'message' => "Deleted Successfully",
    //     ]);
    // }

    /*
        Soft and Hard Deletion of Modules Data
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

    public function restore($id)
    {
        Module::withTrashed()->find($id)->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored Data Successfully",
        ]);
    }

    public function restoreAll()
    {
        Module::onlyTrashed()->restore();
        return response()->json([
            'success' => true,
            'message' => "Restored All Data Successfully",
        ]);
    }
}
