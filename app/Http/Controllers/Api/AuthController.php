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
            'password' => 'required',
        ]);
        if ($validator->passes()) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => true,
                'User' => $user,
                'message' => 'User Create SuccessFully'
            ]);
        }else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function login(Request $request) {
        $validation = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validation->errors(),
                'message' => 'validation error'
            ]);
        }
        if (auth::attempt(['email' => $request->email,'password' => $request->password])) {
           $user = auth::user();

           return response()->json([
                'status' => true,
                'message' => 'User log in successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
                'token_type' => 'bearer'
           ],200);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Email Or Password is Incorrenct'
            ],401);
        }
    }
    public function logout(Request $request) {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status' =>  true,
            'message' => 'User Delete SuccessFully',
            'errors' => $user
        ]);
    }
}
