<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        if($valid->fails())
        {
            return $this->error('Validation error', $valid->errors(), 422);
        }

        $user = User::create($request->all());
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->success($success, 'User registered');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials, true)){
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
            return $this->success($success, 'User login');
        } 
        else{ 
            return $this->error('Unauthorized.', ['error'=>'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {        
        if (Auth::check()) {
            $token = Auth::user()->token();
            $token->revoke();
            return $this->success(['User ID' => Auth::id()], 'User is logout');
        } 
        else{ 
            return $this->error('Unauthorised.', ['error'=>'Unauthorised'] , 401);
        } 
    }
}
