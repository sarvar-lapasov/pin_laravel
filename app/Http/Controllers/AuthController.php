<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','refresh']]);
    }

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return $this->error('Unauthorized', 401);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return $this->error($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        $user->roles()->attach(2);
        return $this->success(
            'User successfully registered',
             $user);
    }

    public function logout() {
        auth()->logout();
        return $this->success('User successfully signed out');
    }
   
    public function refresh() {
        return $this->createNewToken(Auth::refresh());
    }
   
    public function userProfile() {
        return $this->response(new UserResource(auth()->user()));
    }
   
    protected function createNewToken($token){
        return $this->response([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user())
        ]);
    }
}
