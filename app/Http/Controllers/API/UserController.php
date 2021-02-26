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
        $this->middleware('permission:user-update', ['only' => ['updateAdmin']]);
        $this->middleware('permission:update-password', ['only' => ['updatePasswordAdmin']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return $this->success(new UserCollection(User::paginate(20)), 'Users retrieved');
    }

    public function store(Request $request)
    {
        return $this->success(new UserResource(User::create($request->all())), 'User created');
    }

    public function show(User $user)
    {
        return $this->success(new UserResource($user), 'User retrieved');
    }

    // Khusus super-admin
    public function update(Request $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        return $this->success(new UserResource($user), 'User updated');
    }

    public function updateAuth(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        return $this->success(new UserResource($user), 'Info updated');
    }

    // Khusus super-admin
    public function updatePassword(Request $request, User $user)
    {
        $user->update(['password' => $request->password]);
        return $this->success(new UserResource($user), 'Password updated');
    }

    public function updatePasswordAuth(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $user->update(['password' => $request->password]);
        return $this->success(new UserResource($user), 'Password updated');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->success(new UserResource($user), 'User deleted');
    }

    public function setRole(Request $request, User $user)
    {
        $user->syncRoles(Role::findByName($request->role, 'api'));
        return $this->success([], 'Set role '.$request->role.' to user '.$user->name);
    }
}
