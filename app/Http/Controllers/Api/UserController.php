<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class UserController extends Controller
{
    //

    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'min:8']
        ]);
        if (!Auth::attempt($validate)) {
            return response()->json('These credentials are not valide', 401);
        }
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken($user->name)->plainTextToken;
        // return response()->json(['user' => auth()->user(),'message'=>'chihaja']);
        return response()->json(['token' => $token,'user'=>$user],200);
    }
    public function register(Request $request)
    {
       // return response()->json(['register']);
        $validate = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'min:8']
        ]);
        $user = User::create($validate);
        $token = $user->createToken($user->name)->plainTextToken;
        return response()->json(['user'=>$user,'token'=>$token],201);
    }
    public function logout(Request $request) {

        
        // return response()->json(['user'=>$request->user()]);
        $request->user()->currentAccessToken()->delete(); 

        return response()->json(['message' => 'Logged out successfully']);
    }
}
