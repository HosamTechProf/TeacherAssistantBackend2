<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Subject;
use App\MyClass;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function getMySubjects()
    {
		$school_id = Auth::user()->school_id;
		$myClasses = MyClass::find($school_id)->pluck('id')->toArray();
    	$subjects = Subject::whereIn('class_id', $myClasses)->get();
    	foreach ($subjects as $subject) {
    		$myClass = MyClass::find($subject->class_id)->name;
    		$subject->class_name = $myClass;
    	}
    	return $subjects->groupBy('class_name');
    }

    public function addSubject(Request $request)
    {
    	$subject = new Subject;
    	$subject->name = $request->name;
    	$subject->class_id = $request->class_id;
    	$subject->save();
    	return response()->json(['status'=>true]);
    }
}
