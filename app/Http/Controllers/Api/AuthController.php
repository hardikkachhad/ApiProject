<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->passes()) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'User Save Successfully',
                'errors' => $user
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function login(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication failed',
                'errors' => $validator->errors()
            ], 404);
        }
    
        if (auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authuser = Auth::user();
    
            return response()->json([
                'status' => true,
                'message' => "User logged in successfully",
                'token' => $authuser->createToken("api token")->plainTextToken,
                'token_type' => 'bearer'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Email and Password do not match",
                'token_type' => 'bearer'
            ], 401);
        }
    }
    public function logout(Request $request) {
     $user = $request->user();
     $user->tokens()->delete();
     return response()->json([
        'status' => true,
        'message' => 'User log Out Sucessfully',
        'errors' => $user
     ]);
    }
}
