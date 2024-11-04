<?php

namespace App\Http\Controllers;

use App\Http\Requests\Loginrequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::query()->create($data);
            $token = $user->createToken('register')->plainTextToken;
            return response()->json([
                'messenge' => "Tao moi thanh cong",
                'token' => $token,
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);

            return response()->json([
                'messenge' => "Tao moi khong thanh cong",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();
            return response()->json([
                'messenge' => 'Logout Thanh cong !!!'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);

            return response()->json([
                'messenge' => 'Logout Khong Thanh cong !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function login(Loginrequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::query()->where('email', $data['email'])->first();
            if (!$user) {
                return response()->json([
                    'messenge' => 'email khong ton tai !!!'
                ], Response::HTTP_OK);
            }
            if (Hash::check($data['password'], $user->password)) {
                $token = $user->createToken('login')->plainTextToken;
                return response()->json([
                    'messenge' => "Login thanh cong",
                    'token' => $token,
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return response()->json([
                'messenge' => 'Login Khong Thanh cong !!!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
