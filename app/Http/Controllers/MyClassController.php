<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\MyClass;
use App\School;

class MyClassController extends Controller
{
    public function addClass(Request $request)
    {
        $school_id = Auth::user()->school_id;
    	$user_id = Auth::user()->id;
    	$myClass = new MyClass;
    	$myClass->name = $request->name;
        $myClass->school_id = $school_id;
    	$myClass->user_id = $user_id;
    	$myClass->save();
    	return response()->json(['status' => true]);
    }

    public function getMyClasses()
    {
    	$user = Auth::user();
    	$myClasses = $user->classes;
    	return response()->json(['classes' => $myClasses]);
    }

    public function search($name)
    {
    	$school_id = Auth::user()->school_id;
    	$myClasses = MyClass::where('school_id', $school_id)->where('name', 'LIKE', "%{$name}%")->get();
    	return response()->json(['result' => $myClasses]);
    }

    public function getAllClassData($classId)
    {
        $myClass = MyClass::find($classId);
        $class_students = $myClass->students;
        $school = school::find($myClass->school_id);
        $myClass['school_name'] = $school->name;
        return response()->json(['class_data'=>$myClass, 'students'=>$class_students]);
    }
}
