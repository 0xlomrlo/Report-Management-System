<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::orderBy('id')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'roleName' => 'required',
            'permissions' => 'required',
        ]);

        $input = [
            'roleName' => $request->get('roleName'),
            'permissions' => $request->get('permissions'),
            
        ];

        $role = Role::create(['name' => $input['roleName']]);
        $role->givePermissionTo($input['permissions']);

        return redirect('roles')->with('success', trans('messages.success_create'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'roleName' => 'required',
            'permissions' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $input = [
            'roleName' => $request->get('roleName'),
            'permissions' => $request->get('permissions'),
        ];
        $role->revokePermissionTo($permissions);
        $role->givePermissionTo($input['permissions']);

        return redirect('roles')->with('success', trans('messages.success_update'));
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if ($role->name == 'admin') {
            return redirect('roles')->with('error', trans('messages.error'));
        }else{
            $role->delete();
        }
        
        return redirect('roles')->with('success', trans('messages.success_delete'));
    }
}
