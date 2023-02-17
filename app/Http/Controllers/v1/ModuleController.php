<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;


class ModuleController extends Controller
{
    public function list()
    {
        $modules = Module::all()->simplePaginate(10);
        return response()->json([
            'success'   => true,
            'message'   => "Module List",
            'data'      => $modules
        ]);
    }

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

    public function show($id)
    {
        $module = Module::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $module
        ]);
    }

    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);
        $request->validate([
            'module_code'   => 'required',
            'name'          => 'required',
            'is_in_menu'    => 'required'
        ]);
        $module->update($request->only('name', 'module_code', 'is_in_menu'));
        return response()->json([
            'success' => true,
            'message' => "Updated Successfully",
        ]);
    }

    public function destroy($id)
    {
        Module::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => "Deleted Successfully",
        ]);
    }
}
