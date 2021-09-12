<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\{Role,Permission};
use Validator;
use App\Http\Resources\{RoleResource,PermissionResource};

class RoleController extends Controller
{
    /**
     * @OA\Get(
     *    path="/role",
     *    operationId="getRolesList",
     *    tags={"Roles & Permissions"},
     *    summary="Get list of Roles",
     *    security={
     *        {"passport": {}},
     *    },
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\MediaType(
     *           mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Forbidden"
     *    )
     * )
     **/
    public function index()
    {
        return $this->success(RoleResource::collection(Role::all()), 'Roles retrieved successfully.');
    }

    /**
     * @OA\Post(
     *    path="/role",
     *    operationId="addRole",
     *    tags={"Roles & Permissions"},
     *    summary="Add Role",
     *    security={
     *        {"passport": {}},
     *    },
     *    @OA\Parameter(
     *        name="name",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="string"
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\MediaType(
     *           mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Forbidden"
     *    ),
     *    @OA\Response(
     *        response=422,
     *        description="Unprocessable Entity"
     *    )
     * )
     **/
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name'
        ]);

        $data = Role::firstOrCreate(['name' => $request->name]);
        return $this->success(new RoleResource($data), 'Role created successfully.');
    }

    /**
     * @OA\Delete(
     *    path="/role/{role}",
     *    operationId="deleteRole",
     *    tags={"Roles & Permissions"},
     *    summary="Delete role",
     *    security={
     *        {"passport": {}},
     *    },
     *    @OA\Parameter(
     *        name="role",
     *        in="path",
     *        required=true,
     *        description="Role id",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\MediaType(
     *           mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Forbidden"
     *    ),
     *    @OA\Response(
     *        response=422,
     *        description="Unprocessable Entity"
     *    )
     * )
     **/
    public function destroy(Role $role)
    {
        $role->delete();
        return $this->success(new RoleResource($role), 'Role deleted successfully.');
    }

    /**
     * @OA\Get(
     *    path="/permission",
     *    operationId="getPermissionList",
     *    tags={"Roles & Permissions"},
     *    summary="Get list of Permissions",
     *    security={
     *        {"passport": {}},
     *    },
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\MediaType(
     *           mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Forbidden"
     *    )
     * )
     **/
    public function permission()
    {
        return $this->success(PermissionResource::collection(Permission::all()), 'Permissions retrieved successfully.');
    }

    /**
     * @OA\Get(
     *    path="/role/permission/{role}",
     *    operationId="getRoleHasPermission",
     *    tags={"Roles & Permissions"},
     *    summary="Get role has permissions",
     *    security={
     *        {"passport": {}},
     *    },
     *    @OA\Parameter(
     *        name="role",
     *        in="path",
     *        required=true,
     *        description="Role name",
     *        @OA\Schema(
     *            type="string"
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\MediaType(
     *           mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Forbidden"
     *    ),
     *    @OA\Response(
     *        response=422,
     *        description="Unprocessable Entity"
     *    )
     * )
     **/
    public function hasPermission($role)
    {
        $getRole = Role::findByName($role);
        $hasPermission = $getRole->permissions()->get();
        return $this->success($hasPermission, 'Role has permissions retrieved successfully.');
    }

    /**
     * @OA\Put(
     *    path="/role/permission/{role}",
     *    operationId="setRolePermission",
     *    tags={"Roles & Permissions"},
     *    summary="Set role permissions",
     *    security={
     *        {"passport": {}},
     *    },
     *    @OA\Parameter(
     *        name="role",
     *        in="path",
     *        required=true,
     *        description="Role name",
     *        @OA\Schema(
     *            type="string"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="permission",
     *        in="query",
     *        required=true,
     *        description="Permissions name",
     *        @OA\Schema(
     *            type="array",
     *            @OA\Items(
     *                type="string"
     *            ),
     *            minItems=1
     *        ),
     *        style="form"
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\MediaType(
     *           mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Forbidden"
     *    ),
     *    @OA\Response(
     *        response=422,
     *        description="Unprocessable Entity"
     *    )
     * )
     **/
    public function setRolePermission(Request $request, $role)
    {
        $role = Role::findByName($role);
        $role->syncPermissions($request->permission);
        return $this->success($request->permission, 'Permission to Role Saved successfully.');
    }
}
