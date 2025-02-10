<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RegisterResource;

class AuthController extends Controller {

    public function registerindex()
    {
        return view('authentication.register');
    }

    public function loginindex()
    {
        return view('authentication.login');
    }

    // ✅ Register API
    public function register(RegisterRequest $request) {

        $token = $request->header('Authorization'); // Bearer Token read karo
        $expectedToken = 'Bearer ' . config('app.api_secret'); // Expected token
    
        if ($token !== $expectedToken) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized! Invalid API Token.'
            ], 401);
        }

        // Get user IP
        $userIp = $request->ip();
        $validatedData = $request->validated();
    
        Log::channel('registerlog')->info('User Registration Attempt', [
            'ip' => $userIp,
            'data' => $validatedData
        ]);
    
        // Generate referral code & check if referred_by exists
        $validatedData['referral_code'] = strtoupper(substr(md5(time()), 0, 6));
        $validatedData['referred_by'] = $request->has('referral_code') ? $request->referral_code : null;
    
        try {
            $user = User::create($validatedData);
            return response()->json([
                'status' => 201,
                'message' => 'User registered successfully!',
                'token' => $user->createToken('API Token')->plainTextToken,
                'data' => new RegisterResource($user)
            ], 201);
        } catch (\Exception $e) {
            Log::error('User Registration Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 500,
                'message' => "Registration Failed! Please try again."
            ], 500);
        }
    }
    
    // ✅ Login API
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
            'password' => 'required|numeric' // ❌ 'number' galat hai, Laravel me 'numeric' use hota hai
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }
    
        // ✅ Validation Pass, Login Logic Yahan Likho
        $validatedData = $validator->validated();
        $user = User::where('mobile', $validatedData['mobile'])->first();
    
        if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials!'
            ], 401);
        }
    
        // ✅ Single Device Login
        $user->tokens()->delete();
    
        // ✅ Generate Token
        $token = $user->createToken('API Token')->plainTextToken;
    
        return response()->json([
            'status' => 200,
            'message' => 'Login successful!',
            'token' => $token,
            'data' => $user
        ], 200);
    }

    // ✅ Logout API
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}

