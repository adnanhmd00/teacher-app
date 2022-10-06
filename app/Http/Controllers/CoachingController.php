<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coaching;
use Str;

class CoachingController extends Controller
{
    public function index(){
        // $coachings = Teacher::where('status', 1)->get();
        $coachings = Coaching::all();
        return response()->json([
            'data' => $coachings,
            'status' => '200',
            'message' => 'Showing All Coaching'
        ]);
    }

    public function store(Request $request){
        
        $coaching = new Coaching;
        $coaching->name = $request->name;
        $coaching->email = $request->email;
        $coaching->phone = $request->phone;
        $coaching->address = $request->address;
        $coaching->image = $request->image;
        $coaching->pincode = $request->pincode;
        $coaching->status = '0';
        $coaching->description = $request->description;
        $coaching->password = $request->password;

        // $exists = Teacher::where('email', $request->email)->first();
        // if($exists->email == $request->email){
        //     return response()->json([
        //         'message' => 'This email already exists',
        //     ]);
        // } 

        if($request->hasFile('image')){
            $random = Str::random(10);
            $imgName = $random.'.'.$request->image->extension();
            // return $imgName;
            $coaching->image = $request->image->storeAs('coachings', $imgName, 'public');
        }

        if($coaching->save()){
            return response()->json([
                'data' => $coaching,
                'message' => 'Coaching saved successfully.',
                'status' => '200'
            ]);
        }
    }

    public function update(Request $request, $id){
        $coaching = Coaching::findOrFail($id);
        $coaching->name = $request->name;
        $coaching->phone = $request->phone;
        $coaching->address = $request->address;
        $coaching->image = $request->image;
        $coaching->pincode = $request->pincode;
        $coaching->status = '0';
        $coaching->description = $request->description;
        $coaching->password = $request->password;

        // $exists = Teacher::where('email', $request->email)->first();
        // if($exists->email == $request->email){
        //     return response()->json([
        //         'message' => 'This email already exists',
        //     ]);
        // } 

        if($request->hasFile('image')){
            $random = Str::random(10);
            $imgName = $random.'.'.$request->image->extension();
            // return $imgName;
            $coaching->image = $request->image->storeAs('coachings', $imgName, 'public');
        }

        if($coaching->save()){
            return response()->json([
                'data' => $coaching,
                'message' => 'Coaching Updated successfully.',
                'status' => '200'
            ]);
        }
    }

    public function activateCoaching($id){
        $coaching = Coaching::findOrFail($id);
        $coaching->status = 1;

        if($coaching->save()){
            return response()->json([
                'data' => $school,
                'message' => 'coaching activated successfully.',
                'status' => '200'
            ]);
        }
    }


    public function deactivateCoaching($id){
        $coaching = Coaching::findOrFail($id);
        $coaching->status = 0;

        if($coaching->save()){
            return response()->json([
                'data' => $school,
                'message' => 'coaching deactivated successfully.',
                'status' => '200'
            ]);
        }
    }
}
