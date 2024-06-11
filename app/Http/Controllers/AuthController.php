<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name'         =>  'required|String|max:255',
            'password'     =>  'required|String|min:6',
        ]);

        $user=User::create([
            'name'          =>  $request->name,
            'password'      =>  Hash::make($request->password),
        ]);
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }


    public function login(Request $request){
        $request->validate([
            'name'          =>  'required|String|max:255',
            'password'      =>  'required|String|min:6'
        ]);

        if (Auth::guard('web')->attempt($request->only('name', 'password'))) {
            $user = Auth::guard('web')->user();
            // $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['message' => 'Login successful', 'user' => $user]);

        } else {
            // Try logging in as an admin
            if (Auth::guard('admin')->attempt($request->only('name', 'password'))) {
                $admin = Auth::guard('admin')->user();
                // $token = $admin->createToken('authToken')->plainTextToken;
                return response()->json(['message' => 'Admin login successful', 'admin' => $admin]);
            } else {
                return response()->json(['message' => 'Invalid credentials'], 400);
            }

    }
}
}
