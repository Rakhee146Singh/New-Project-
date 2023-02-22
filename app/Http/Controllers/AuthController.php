<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //function for registration
    public function register(Request $request)
    {
        //validations
        $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required|nullable',
            'email'      => 'required|email',
            'password'   => 'required|confirmed',
        ]);

        //checking email exists or not with message
        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already exists',
                'status'  => 'failed'
            ], 200);
        }

        //User Create
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);
        //generate token for users to access
        $token = $user->createToken($request->email)->plainTextToken;

        //Response in json format with success message
        return response([
            'token'     => $token,
            'users'     => $user,
            'message'   => 'Registered successfully',
            'status'    => 'success'
        ], 200);
    }

    //Function for  Users login
    public function login(Request $request)
    {
        //validation for login
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        //Check the request of users with email and password
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {

            //generate token for users to access
            $token = $user->createToken($request->email)->plainTextToken;

            //Response in json format with success message
            return response([
                'token'     => $token,
                'users'     => $user,
                'message'   => 'Log In successfully',
                'status'    => 'success'
            ], 200);
        }
        //Response in json format with failed message
        return response([
            'message' => 'The Provided Credentials are Incorrect.',
            'status'  => 'failed'
        ], 401);
    }


    //function for user logout
    public function logout()
    {
        auth()->user()->tokens()->delete();
        //Response in json format with success message
        return response([
            'message' => 'Log Out Successfully',
            'status' => 'success'
        ], 200);
    }
}
