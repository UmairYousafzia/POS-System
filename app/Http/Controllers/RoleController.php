<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        abort_if(! auth()->user()->can('role.view'), 403);
        $roles = Role::with('permissions')->orderBy('id', 'ASC')->get();

        return view('setting.roles.index', compact('roles'));

    }

    public function create()
    {
        abort_if(! auth()->user()->can('role.create'), 403);
        return view('setting.roles.create');
    }

    public function store(Request $request)
    {
        abort_if(! auth()->user()->can('role.create'), 403);

        $data = $request->validate([
            'role' => ['required', 'string', 'max:191', 'unique:roles,name'],
            'permissions' => ['required', 'array'],
            
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $data['role']
            ]);

            $role->syncPermissions($request->permissions);
            DB::commit();



        } catch (\Exception|\Error $error) {
            DB::rollBack();
            Alert::error('Error', 'Something Went Wrong' . $error->getMessage());
        }

        return redirect()->route('settings.roles.index');
    }

    public function edit($id)
    {
        $role=Role::with('permissions')->findOrFail($id);

        $permissions = [];
        foreach ($role->permissions as $permission){
            $permissions []=$permission->name;
        }
        return view('setting.roles.edit', compact('role', 'permissions'));

    }

    public  function update(Request $request, $id)
    {
        $data = $request->validate([
            'role' => [
                'required',
                'string',
                'max:191',
                Rule::unique('roles', 'name')->ignore($id)
            ],
            'permissions' => ['required', 'array']
        ]);

        $role = Role::findOrFail($id);

        $role->update([
            'name' => $data['role']
        ]);

        $role->syncPermissions($request['permissions']);


        return redirect()->route('settings.roles.index');


    }

    public function destroy($id)
    {
        abort_if(! auth()->user()->can('role.delete'), 403);
        $data=Role::with('permissions')->findOrFail($id);

        $data->delete();
        Alert::success('Deleted!', 'delete successfully');
        return redirect()->route('settings.roles.index');
    }
}
