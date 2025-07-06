<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $data = new User();

        $rules = [
            'username' => 'required|max:255|unique:users,username',
            'password' => 'required|min:8',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Register failed, please check again',
                'data' => $validate->errors()
            ], 401);
        }

        $data->username = $request->username;
        $data->password = bcrypt($request->password);
        $data->role_id = Role::where('name', 'customer')->first()->id;

        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Register success',
            'username' => $data->username
        ], 200);
    }

    public function Login(Request $request)
    {

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'data' => $validate->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['username', 'password']))) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect username or password',
                
            ], 401);
        }

        $data = User::with(['role', 'document'])->where('username', $request->username)->first();

        return response()->json([
            'success' => true,
            'message' => 'Login success',
            'data_user' => [
                'username' => $data->username,
                'role' => $data->role->name,
                // 'document' => $data->document,
                'token' => $data->createToken('auth-user')->plainTextToken
            ]
        ], 200);
        
    }
}
