<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
        /**
     * @OA\Post(
     *   path="/register",
     *   tags={"Authentication"},
     *   summary="Register",
     *   operationId="register",
     *
     *   @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function register(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        if($valid->fails())
        {
            return $this->error('Validation error', $valid->errors(), 422);
        }

        $user = User::create($request->all());
        $success['token'] =  'Bearer '.$user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->success($success, 'User registered successfully.');
    }

    /**
     * @OA\Post(
     *   path="/login",
     *   tags={"Authentication"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string",
     *          format="password"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function login(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($valid->fails())
        {
            return $this->error('Validation error', $valid->errors(), 422);
        }
        
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials, true)){
            $user = Auth::user(); 
            $success['token'] =  'Bearer '.$user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
            return $this->success($success, 'User login successfully.');
        } 
        else{ 
            return $this->error('Login failed.', ['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * @OA\Get(
     *    path="/profile",
     *    operationId="getDetailUserLogin",
     *    tags={"Users"},
     *    security={
     *        {"passport": {}},
     *    },
     *    summary="Get detail of user login",
     *    description="Returns detail of user login",
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
     *        response=400,
     *        description="Bad Request"
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="not found"
     *   ),
     * )
     **/
    public function show()
    {
        return $this->success(new AuthResource(User::findOrFail(auth()->id())), 'Profile information retrieved.');
    }

    /**
     * @OA\Post(
     *    path="/logout",
     *    operationId="logout",
     *    tags={"Authentication"},
     *    summary="Logout",
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
     * )
     **/
    public function logout(Request $request)
    {        
        if (Auth::check()) {
            $token = Auth::user()->token();
            $token->revoke();
            return $this->success(['User ID' => Auth::id()], 'User is logout');
        } 
        else{ 
            return $this->error('Login required.', ['error'=>'Unauthorized'] , 401);
        } 
    }
}
