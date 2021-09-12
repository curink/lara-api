<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as Controller;
use App\Http\Requests\UserRequest as Request;
use App\Models\User;
use Validator;
use App\Http\Resources\{UserResource,UserCollection};
use Spatie\Permission\Models\{Role,Permission};

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list|user-detail|user-create|user-update|user-delete|update-password', ['only' => ['index']]);
        $this->middleware('permission:user-detail', ['only' => ['show']]);
        $this->middleware('permission:user-create', ['only' => ['store']]);
        $this->middleware('permission:user-update', ['only' => ['update']]);
    }

    /**
     * @OA\Get(
     *    path="/user",
     *    operationId="getUserList",
     *    tags={"Users"},
     *    summary="Get list of user accounts",
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
        return $this->success(new UserCollection(User::paginate(20)), 'Users retrieved Successfully.');
    }

    /**
     * @OA\Post(
     *    path="/user",
     *    operationId="addUser",
     *    tags={"Users"},
     *    summary="Add user account",
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
     *    @OA\Parameter(
     *        name="email",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="string"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="phone",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="number"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="password",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="string",
     *            format="password"
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
        return $this->success(new UserResource(User::create($request->all())), 'User created Successfully.');
    }

    /**
     * @OA\Get(
     *    path="/user/{user}",
     *    operationId="getUser",
     *    tags={"Users"},
     *    summary="Get user account by user id",
     *    security={
     *        {"passport": {}},
     *    },
     *    @OA\Parameter(
     *        name="user",
     *        in="path",
     *        required=true,
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
     *    )
     * )
     **/
    public function show(User $user)
    {
        return $this->success(new UserResource($user), 'User retrieved Successfully.');
    }

    /**
     * @OA\Put(
     *    path="/user/{user}",
     *    operationId="UpdateUser",
     *    tags={"Users"},
     *    summary="Update user account by user id",
     *    security={
     *        {"passport": {}},
     *    },
     *    @OA\Parameter(
     *        name="user",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *            type="integer"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="name",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="string"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="email",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="string"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="phone",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="number"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="password",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="string",
     *            format="password"
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
    public function update(Request $request, User $user)
    {
        /*if($request->foto != ''){
            
        }*/
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        return $this->success(new UserResource($user), 'User updated Successfully.');
    }

    /**
     * @OA\Post(
     *    path="/user/set-role/{user}",
     *    operationId="SetRoleUser",
     *    tags={"Users"},
     *    summary="Set role for user account by user id",
     *    security={
     *        {"passport": {}},
     *    },
     *    @OA\Parameter(
     *        name="user",
     *        in="path",
     *        required=true,
     *        description="User id",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="role",
     *        in="query",
     *        required=true,
     *        description="(Role name)",
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
    public function setRole(Request $request, User $user)
    {
        $user->syncRoles(Role::findByName($request->role, 'api'));
        return $this->success([], 'Set role '.$request->role.' to user '.$user->name.' Successfully.');
    }
}
