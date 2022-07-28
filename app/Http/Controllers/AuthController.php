<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use App\Models\Poster;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create new AuthController instance.
     * 
     * @return void
     */
    public function __construct() {
        // $this->middleware('auth:poster', ['except' => ['posterLogin', 'posterRegister']]);
    }

    /**
     * Get a JWT via given credentials.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function posterLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        
        $token = auth('poster')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }
        $poster = auth('poster')->user();
        return response()->json([
            'success' => true,
            // 'user' => $poster,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]
        ]);
        
    }

    /**
     * Register a User
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function posterRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|between:2,100',
            'lname' => 'required|string|between:2,100',
            'matricNo' => 'required|string',
            'email' => 'required|string|email|max:100|unique:posters',
            'password' => 'required|string|min:6',
            'phoneNum' => 'required|string|min:11',
            'school' => 'required|string',
            'depart' => 'required|string',
            'level' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $poster = Poster::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)]
        ));
        return response()->json([
            'status' => 'success',
            'message' => 'A new Student is successfully registered',
            'poster' => $poster,
        ]);
    }

    /**
     * Log the Poster out
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function posterLogout()
    {
        auth('poster')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'User successfully signed out',
        ]);
    }

    /**
     * Refresh a token
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // return $this->posterCreateNewToken(auth()->refresh());
        return response()->json([
            'status' => 'success',
            // 'user' => auth('poster')->user(),
            'authorization' => [
                'token' => auth('poster')->refresh(),
                'type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]
        ]);
    }
    /**
     * Get the authenticated User.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth('poster')->user());
    }
    /**
     * Get the token array structure
     * 
     * @param string $token
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function posterCreateNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth('poster')->user()
        ]);
    }
}
