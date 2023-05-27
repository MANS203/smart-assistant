<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'age' => 'required|integer',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
        'gender' => 'required|string|in:male,female',
        'department' => 'required|string',
        'nationalID' => 'nullable|string',
        'role' => 'required|string|in:patient,doctor',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = User::create([
        'name' => $request->name,
        'age' => $request->age,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'gender' => $request->gender,
        'department' => $request->department,
        'nationalID' => $request->nationalID,
        'role' => $request->role,
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json(['status' => true, 'user' => $user, 'token' => $token], 201);
}

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }
        
        $token = JWTAuth::attempt($credentials);
        $user = Auth::user();
        return response()->json(['status' => true, 'message' => 'Login done successfully' ,'user' => $user, 'token' => $token]);
    }
}
