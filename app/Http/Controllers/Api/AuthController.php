<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [], [
            'name' => 'name',
            'email' => 'email',
            'password' => 'password',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, 'Register Validation Error', $validator->messages()->all());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $data['token'] = $user->createToken('User-Token')->plainTextToken;
        $data['name'] = $user->name;
        $data['email'] = $user->email;

        return ApiResponse::sendResponse(201, 'User Account Created Successfully', $data);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required'],
        ], [], [
            'email' => 'email',
            'password' => 'password',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, 'Login Validation Error', $validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // if ($user) {
            //     return 'alreadt Logged In';
            // }
            $data['token'] = $user->createToken('Admin-Api')->plainTextToken;
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            return ApiResponse::sendResponse(200, 'User Logged-In Successfully', $data);
        } else {
            return ApiResponse::sendResponse(401, "User Credentials Does't Exits", []);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200,'Logged Out Successfully',[]);
    }
}
