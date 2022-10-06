<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Carbon\Carbon;
use Hash;
use Str;

class RegisterController extends Controller
{
    public function register(Request $request){
        try{
            if($request->has('email') && $request->has('phone')){
                $user = User::where('email', $request->email)->orWhere('phone', $request->phone)->first();
                if(!empty($user)){
                    return response()->json([
                        'status' => 401,
                        'message' => 'Email or Phone already exists!'
                    ]);
                }
            }
            $slug = $request->name;
            $user_slug = str_replace(' ', '', substr($slug, 0, 5).Str::random(5));
            $user = new User;
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->status = 1;
            $user->pincode = $request->pincode;
            $user->password = Hash::make($request->password);
            if($user->save()){
                $this->sendOtp($user->id);
            }
        
            $credentials = request(['email', 'password']);
    
            if(!Auth::attempt($credentials)){
                return response()->json(['message' => 'Please Check Your Password & Try Again', 'status' => 401]);
            }
    
            $user = $request->user();
    
            // $tokenResult = $user->createToken('Personal Access Token');
            // $token = $tokenResult->token;
            // $token->expires_at = Carbon::now()->addYear(1);
            // $token->save();
    
            // return response()->json(['message' => 'You have been registered. Happy Shopping'], 200);
            return response()->json([
                'status' => 200,
                'data' => [ 
                    'message' => 'Registered Successfully.',
                    'user' => isset($user) ? $user : 'Not Found!!',
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

    public function sendOtp($id){
        $user = User::findOrFail($id);
        
        $otp = $user->otp;
		$phone = $user->phone;
		$authKey = "327291AVnDZUSxOgoq5ea3f8f3P1";
		$mobileNumber = "9569096496";
		$senderId = "OWOIND";
		$message = "Forget password code".$otp;
		$route = 4;
		$postData = array(
		'authkey' => $authKey,
		'mobiles' => $mobileNumber,
		'message' => $message,
		'sender' => $senderId,
		'route' => $route,
		'DLT_TE_ID' => "1007658279244956698"
		);


		$url = "https://api.msg91.com/api/sendhttp.php/api/sendhttp.php?for_panel_id=1&mobiles=".$phone."&authkey=327291AVnDZUSxOgoq5ea3f8f3P1&DLT_TE_ID=1007658279244956698&route=4&sender=OWOIND&message=Welcome%2Bto%2BOWO%2B%0A%0A%2BThis%2Bis%2Bmobile%2Bnumber%2Bverification%2Bfrom%2BOWO%2B%0A%0A%2BPlease%2Benter%2Bthe%2BOTP%3A".$otp."&encrypt=0";

		$curl = curl_init();
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,

		CURLOPT_CUSTOMREQUEST => "GET",
		));
		$response = curl_exec($curl);
		curl_close($curl);
	}

    public function resendOtp(Request $request){
        $otp = rand(100000, 999999);
        $user = User::where('email', $request->email)->orWhere('phone', $request->email)->first();
        if($user == '' || $user == null){
            return response()->json([
                'status' => 404,
                'message' => 'User Not Found!'
            ]);
        }
        $user->otp = $otp;
        $user->save();
            $phone = $user->phone;
            $authKey = "327291AVnDZUSxOgoq5ea3f8f3P1";
            $mobileNumber = "9569096496";
            $senderId = "OWOIND";
            $message = "Forget password code".$otp;
            $route = 4;
            $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route,
            'DLT_TE_ID' => "1007658279244956698"
		);


		$url = "https://api.msg91.com/api/sendhttp.php/api/sendhttp.php?for_panel_id=1&mobiles=".$phone."&authkey=327291AVnDZUSxOgoq5ea3f8f3P1&DLT_TE_ID=1007658279244956698&route=4&sender=OWOIND&message=Welcome%2Bto%2BOWO%2B%0A%0A%2BThis%2Bis%2Bmobile%2Bnumber%2Bverification%2Bfrom%2BOWO%2B%0A%0A%2BPlease%2Benter%2Bthe%2BOTP%3A".$otp."&encrypt=0";

		$curl = curl_init();
		$curl = curl_init();
		curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_CUSTOMREQUEST => "GET",
		));
		$response = curl_exec($curl);
		curl_close($curl);
        
        return response()->json([
            'status' => 200,
            'message' => 'Otp sent successfully!',
            'data' => $user
        ]);
    }

    public function verifyOtp($id, $otp){
        try{
            if(isset($otp)){
                $user = User::findOrFail($id);
                if($otp == $user->otp){
                    $user->verified = 1;
                    $user->save();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Otp verified successfully!'
                    ]);
                }
    
                return response()->json([
                    'status' => 401,
                    'message' => 'Otp not matched!'
                ]);
            }
        }
        catch(Exception $e){
            return response()->json([
                'message' => 'Something Went Wrong',
                'error' => $e
            ]);
        }
    }

    public function forgetPassword(Request $request){
        try{
            if($request->has('email')){
                $user = User::where('email', $request->email)->orWhere('phone', $request->email)->first();
                if($user != '' || $user != null){
                    $user->otp = rand(100000, 999999);
                    $user->save();
                    $this->sendOtp($user->id);
                    return response()->json([
                        'status' => 200,
                        'message' => 'Otp sent successfully.',
                        'data' => $user
                    ]);
                }else{
                    return response()->json([
                        'status' => 404,
                        'message' => 'Email Or Phone Number Not Found.',
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 401,
                    'message' => 'Please enter email or phone.'
                ]);
            }
        }
       catch(Exception $e){
           return response()->json([
               'message' => 'Something Went Wrong',
               'error' => $e
           ]);
       }
    }

    public function confirmPassword(Request $request){
        try{
            $user = User::where('id', $request->user_id)->first();
            if(empty($user)){
                return response()->json([
                    'status'=>404, 
                    'message'=>'User Not Found.'
                ]);
            }
            if($user != '' || $user != null){
                if($request->password == $request->confirm_password){
                    $user->password = Hash::make($request->password);
                    if($user->save()){
                        return response()->json([
                            'status'=>200, 
                            'message'=>'Password changed successfully.'
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => 404,
                        'message'=>'Password and confirm password doesn\'t match.'
                    ]);
                }
            }
        }catch(Exception $e){

        }
    }
}