<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Str;

class StudentController extends Controller
{
    //
      //
      public function index(){
        // $teachers = Teacher::where('status', 1)->get();
        $users = Student::all();
        return response()->json([
            'data' => $users,
            'status' => '200',
            'message' => 'Showing All Students'
        ]);
    }
    public function store(Request $request){
            
        $user = new Student;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->pincode = $request->pincode;
        $user->status = '1';   
        

        if($user->save()){
            return response()->json([
                'data' => $user,
                'message' => 'Student Registered successfully.',
                'status' => '200'
            ]);
        }
    }

    public function update(Request $request, $id){
        $user =  Student::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->pincode = $request->pincode;

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
            $user->image = $request->image->storeAs('students', $imgName, 'public');
        }

        if($user->save()){
            return response()->json([
                'data' => $user,
                'message' => 'Updated successfully.',
                'status' => '200'
            ]);
        }
    }
}
