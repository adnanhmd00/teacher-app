<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use Str;

class SchoolController extends Controller
    {
        public function index(){
            // $schools = Teacher::where('status', 1)->get();
            $schools = School::all();
            return response()->json([
                'data' => $schools,
                'status' => '200',
                'message' => 'Showing All Schools'
            ]);
        }

        public function store(Request $request){
            
            $school = new School;
            $school->name = $request->name;
            $school->email = $request->email;
            $school->phone = $request->phone;
            $school->address = $request->address;
            $school->image = $request->image;
            $school->pincode = $request->pincode;
            $school->status = '0';
            $school->description = $request->description;
            $school->password = $request->password;

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
                $school->image = $request->image->storeAs('schools', $imgName, 'public');
            }

            if($school->save()){
                return response()->json([
                    'data' => $school,
                    'message' => 'School saved successfully.',
                    'status' => '200'
                ]);
            }
        }

        public function update(Request $request, $id){
            $school = School::findOrFail($id);
            $school->name = $request->name;
            $school->phone = $request->phone;
            $school->address = $request->address;
            $school->image = $request->image;
            $school->pincode = $request->pincode;
            $school->status = '0';
            $school->description = $request->description;
            $school->password = $request->password;

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
                $school->image = $request->image->storeAs('schools', $imgName, 'public');
            }

            if($school->save()){
                return response()->json([
                    'data' => $school,
                    'message' => 'Updated successfully.',
                    'status' => '200'
                ]);
            }
        }

        public function activateSchool($id){
            $school = School::findOrFail($id);
            $school->status = 1;

            if($school->save()){
                return response()->json([
                    'data' => $school,
                    'message' => 'School activated successfully.',
                    'status' => '200'
                ]);
            }
        }


        public function deactivateSchool($id){
            $school = School::findOrFail($id);
            $school->status = 0;

            if($school->save()){
                return response()->json([
                    'data' => $school,
                    'message' => 'School deactivated successfully.',
                    'status' => '200'
                ]);
            }
        }
}
