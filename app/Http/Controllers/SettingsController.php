<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Auth;


class SettingsController extends Controller
{
    public function index(){
        try{
            $settings = Settings::where('user_id', Auth::id())->first();

            return response()->json([
                "status" => 201,
                "message" => "Settings User Berhasil Ditampilkan",
                "data" => $settings
            
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => 400,
                 "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        }   
    }

    public function update(Request $request){
        $input = $request->all();
                
                try{
                    $validator = Validator::make($input, [
                        'bahasa' => 'required',
                    ]);

                    if($validator->fails()){
                         return response()->json([
                            "status" => 400,
                            "errors" => $validator->errors(),
                            "data" => null
                        ]);          
                    }
                    $settings = Settings::where('user_id', Auth::id())->first();
                    $settings->user_id = Auth::id();
                    $settings->bahasa = $input['bahasa'];
                    $settings->save();
                    
                    return response()->json([
                        "status" => 201,
                        "message" => "Settings Updated successfully.",
                        "data" => $settings
                    ]);
                }catch(\Exception $e){
                    return response()->json([
                        "status" => 401,
                        "message" => 'Error'.$e->getMessage(),
                        "data" => null
                    ]);
                }
    }

}
