<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'=>'Validation failed',
                'errors'=>$validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);


        return response()->json([
            'message' => 'Registration successfull',
            'data'=>$user
        ],200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'=>'Validation failed',
                'errors'=>$validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user){

            if(Hash::check($request->password, $user->password)){

                $token=$user->createToken('auth-token')->plainTextToken;

                return response()->json([
                    'message'=>'Login successfull',
                    'token'=> $token,
                    'data'=>$user
                ]);

            }else{
                return response()->json([
                    'message'=>'Incorrect credentials',
                ], 400);
            }
        }else{
            return response()->json([
                'message'=>'Incorrect credentials',
            ], 400);
        }
    }

    public function user(Request $request)
    {
        return response()->json([
            'message'=>'User successfully fetched',
            'data'=>$request->user()
        ],200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message'=>'User successfully logged out',
        ], 200);
    }


    
}



