<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $roles = Role::all();


        return Inertia::render('admin/Role/Index', [
            'roles' => $roles,
            'message' => session('swal'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();

        return Inertia::render('admin/Role/Create',[
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'permissions' => 'required | array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        $role->permissions()->attach($request->permissions);

        session()->flash('swal', [
            'title' => 'Excelente',
            'text' => 'Rol creado satisfactoriamente',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.roles.index')->with((json_encode(session('swal'))));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::all();

        return Inertia::render('admin/Role/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required | array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        $role->permissions()->sync($request->permissions);

        session()->flash('swal', [
            'title' => 'Excelente',
            'text' => 'Rol actualizado satisfactoriamente',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.roles.index')->with((json_encode(session('swal'))));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        session()->flash('swal', [
            'title' => 'Eliminado!',
            'text' => 'Tu registro ha sido eliminado satisfactoriamente!',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.roles.index');
    }
}
