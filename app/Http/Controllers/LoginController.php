<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;
use Str;

class LoginController extends Controller
{
    public function login(Request $request){
        try{
            if(is_numeric($request->get('email'))){
                $credentials = ['phone' => $request->get('email'), 'password' => $request->get('password')];
            }else{
                $credentials = request(['email', 'password']);
            }

            if(!Auth::attempt($credentials)){
                return response()->json(['message' => 'Email or password is not correct'], 401);
            }

            
            $user = $request->user();

            // $tokenResult = $user->createToken('Personal Access Token');
            // $token = $tokenResult->token;
            // $token->expires_at = Carbon::now()->addYear(1);
            // $token->save();

            return response()->json([
                'data' => [
                    'status' => 200,
                    'message' => 'Logged In Successfully.',
                    'user' => isset($user) ? $user : 'user Not Found!!',
                    // 'access_token' => $tokenResult->accessToken,
                    // 'token_type' => 'Bearer',
                    // 'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
                ]
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'message' => 'Something Went Wrong',
                'error' => $e
            ]);
        }
    }
}