<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();

        return inertia('admin/Permission/Index',[
            'permissions' => $permissions,
            'message' => session('swal'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('admin/Permission/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        Permission::create($request->all());

        session()->flash('swal', [
            'title' => 'Excelente',
            'text' => 'Permiso creado satisfactoriamente',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.permissions.index')->with((json_encode(session('swal'))));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return inertia('admin/Permission/Edit',[
            'permission' => $permission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $permission->update($request->all());

        session()->flash('swal', [
            'title' => 'Excelente',
            'text' => 'Categoria actualizada satisfactoriamente',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        session()->flash('swal', [
            'title' => 'Eliminado!',
            'text' => 'Tu registro ha sido eliminado satisfactoriamente!',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.permissions.index');
    }
}
