<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Str;

class TeacherController extends Controller
{
    public function index(){
        // $teachers = Teacher::where('status', 1)->get();
        $teachers = Teacher::all();
        return response()->json([
            'data' => $teachers,
            'status' => '200',
            'message' => 'Showing All Teachers'
        ]);
    }

    public function store(Request $request){
        
        $teacher = new Teacher;
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->phone = $request->phone;
        $teacher->address = $request->address;
        $teacher->pincode = $request->pincode;
        $teacher->status = '0';
        $teacher->description = $request->description;
        $teacher->password = $request->password;
        
        // if($request->image) {
            if($request->hasFile('image')){
            $random = Str::random(10);
            $imgName = $random.'.'.$request->image->extension();
            // return $imgName;
            $teacher->image = $request->image->storeAs('teachers', $imgName, 'public');
        }
        if($teacher->save()){
            return response()->json([
                'data' => $teacher,
                'message' => 'Teacher saved successfully.',
                'status' => '200'
            ]);
        }
    }

    public function update(Request $request, $id){
        $teacher = Teacher::findOrFail($id);
        $teacher->name = $request->name;
        $teacher->phone = $request->phone;
        $teacher->address = $request->address;
        $teacher->pincode = $request->pincode;
        $teacher->status = '0';
        $teacher->description = $request->description;
        $teacher->password = $request->password;


        if($request->hasFile('image')) {
            
            $random = Str::random(10);
            $imgName = $random.'.'.$request->image->extension();
            $teacher->image = $request->image->storeAs('teachers', $imgName, 'public');
        }
        // $exists = Teacher::where('email', $request->email)->first();
        // if($exists->email == $request->email){
        //     return response()->json([
        //         'message' => 'This email already exists',
        //     ]);
        // } 

        if($teacher->save()){
            return response()->json([
                'data' => $teacher,
                'message' => 'Updated successfully.',
                'status' => '200'
            ]);
        }
    }

    public function activateTeacher(Request $request, $id){
        $teacher = Teacher::findOrFail($id);
        $teacher-> status = 1;
     
        if($teacher->save()){

            return response()->json([
                'data' => $teacher,
                'message' => 'Teacher has been activated successfully.',
                'status' => '200'
            ]);
        }
    }


    public function deactivateTeacher(Request $request, $id){
        $teacher = Teacher::findOrFail($id);
        $teacher-> status = 0 ;
        if($teacher->save()){

            return response()->json([
                'data' => $teacher,
                'message' => 'Teacher has been deactivated successfully.',
                'status' => '200'
            ]);
        }
    }
}
