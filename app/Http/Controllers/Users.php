<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Users extends Controller
{

    public function index(Request $request)
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            $response = [
                "success" => true,
                'message' => "Login successfully",
                'data' => $user,
                'token' => $token
            ];

            return response()->json($response, 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $response = [
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken
        ];

        return response()->json($response, 200);
    }
}
