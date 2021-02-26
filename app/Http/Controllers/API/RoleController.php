<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Validator;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    public function index()
    {
        return $this->success(RoleResource::collection(Role::all()), 'Roles retrieved');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50'
        ]);

        $data = Role::firstOrCreate(['name' => $request->name]);
        return $this->success(new RoleResource($data), 'Role created');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return $this->success(new RoleResource($role), 'Role deleted');
    }

    public function hasPermission(Request $request)
    {
        $role = $request->get('role');
        $getRole = Role::findByName($role);
        $hasPermission = $getRole->permissions()->get();
        return $this->success($hasPermission, 'Role has permissions retrieved');
    }

    public function setRolePermission(Request $request, $role)
    {
        $role = Role::findByName($role);
        $role->syncPermissions($request->permission);
        return $this->success($request->permission, 'Permission to Role Saved');
    }
}
