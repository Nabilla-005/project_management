<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AuthController extends Controller
{
    // 🔐 Register

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name', // pastikan role valid
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Tambahkan role
        $user->assignRole($validated['role']);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user,
        ]);
    }

    // 🔐 Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'token' => $token,
            'user'  => auth()->user(),
        ]);
    }

    // 🔁 Refresh Token
    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return response()->json(['token' => $newToken]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token cannot be refreshed'], 401);
        }
    }

    // 🚪 Logout
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

    // ✅ Cek user login
    public function me()
    {
        $user = auth()->user();
        $user->load('roles.permissions'); // load role dan permission

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->pluck('name')->first(),
            'roles' => $user->roles,
            'permissions' => $user->getAllPermissions()->pluck('name'), // ✅ tambahkan ini
        ]);
    }

}
