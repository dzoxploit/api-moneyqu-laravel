<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Settings;
use Auth;

class UsersController extends Controller
{
     public function login(){
        
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $settings = Settings::where('user_id',$user->id)->first();
            $success['token'] = $user->createToken('appToken')->accessToken;
            return response()->json([
                'success' => true,
                'token' => $success,
                'user' => $user,
                'settings' => $settings
            ]);
        } else{
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
    }


    public function register(Request $request){
            $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            ]);
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                ], 401);
            }
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            $settings = new Settings;
            $settings->user_id = $user->id;
            $settings->currency_id = 1;
            $settings->bahasa = 1;
            $settings->settings_component_1 = 1;
            $settings->save();
            
            $success['token'] = $user->createToken('appToken')->accessToken;
            return response()->json([
                'success' => true,
                'token' => $success,
                'user' => $user,
                'settings' => $settings
            ]);
    
    }


    public function logout(Request $request){
        if(Auth::user()){
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json([
            'success' => true,
            'message' => 'Logout successfully',
        ]);
        } else{
        return response()->json([
            'success' => false,
            'message' => 'Unable to Logout',
        ]);
        }
    }



}
