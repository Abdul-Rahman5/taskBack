<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    //
    public function register(Request $request){
        //validator
        $validator=  Validator::make($request->all(),[
            "name"=>"required|string|max:255",
            "email"=>"required|email|max:255|unique:users,email",
            "password"=>"required|min:8|confirmed"
        ]);
        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                "message"=>$validator->errors()
            ],301);
        }
        //password hash
        $password=bcrypt($request->password);
        //
        $access_token=Str::random(64);
        //create
        User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$password,
            "access_token"=>$access_token
        ]);
        //message
        return response()->json([
            "message"=>"successfuly",
            "access_token"=>$access_token

    ],201);

    }
    public function login(Request $request){
         //validator
         $validator=  Validator::make($request->all(),[
            "email"=>"required|email|max:255",
            "password"=>"required|min:8"
        ]);
        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                "message"=>$validator->errors()
            ],301);
        }
        //check email and password
       $user= User::where("email","=",$request->email)->first();
       if ($user !==null) {
        //password
        $oldPassword=$user->password;
       $isVerified= Hash::check($request->password,$oldPassword);
        if ($isVerified) {
            # update
            $access_token=Str::random(64);

            $user->update([
                "access_token"=>$access_token
            ]);
            return response()->json([
                "message"=>"successfuly ",
                "access_token"=>$access_token

            ],201);

        }else{
            return response()->json([
                "message"=>"credintials  not correct"
            ],404);
        }
       }else{
        return response()->json([
            "message"=>"this account  not exist"
        ],404);
       }

        //login
        //message
    }

    public function logout(Request $request){
        //access_token
      $access_token=  $request->header("access_token");
      if ($access_token !==null) {
      $user=  User::where("access_token","=",$access_token)->first();
        if ($user !==null) {
            $user->update([
                "access_token"=>null
            ]);
            return response()->json([
                "message"=>"you logged out successfuly"
            ],201);
        } else {
            return response()->json([
                "message"=>"access token not correct"
            ],404);
        }



      } else {
        return response()->json([
            "message"=>"access token not found"
        ],404);
      }

    }



}
