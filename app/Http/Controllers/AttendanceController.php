<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyClass;
use App\Lecture;
use App\Student;

class AttendanceController extends Controller
{
    public function getDayLectures(Request $request)
    {
    	$myClass = MyClass::find($request->class_id);
    	$lectures = $myClass->lectures->where('lectureDate', $request->date)->flatten(1);
    	return $lectures;
    }

    public function addAttendance(Request $request)
    {
    	$studentId = $request->studentId;
    	$lectureId = $request->lectureId;
    	$lecture = Lecture::find($lectureId);
    	$student = $lecture->students->where('id', $studentId)->flatten(1);
    	if (count($student) == 0) {
    		$lecture->students()->attach($studentId);
    	}else{
    		if ($student[0]->pivot['type'] == 1) {
    			$lecture->students()->updateExistingPivot($studentId, ['type' => 0]);
    		}else if ($student[0]->pivot['type'] == 0) {
    			$lecture->students()->detach($studentId);
    		}
    	}
    	return response()->json(['status'=>true]);
    }

    public function getAttendanceTakePage($lectureId)
    {
    	$students = Lecture::find($lectureId)->students();
    	$students2 = Lecture::find($lectureId)->students();

    	$present = $students->wherePivot("type", '=', "1")->get();
		$present = $present->map(function ($user) {
		    return $user->only(['id'])['id'];
		});

    	$absent = $students2->wherePivot("type", '=', "0")->get();
		$absent = $absent->map(function ($user) {
			return $user->only(['id'])['id'];
		});

    	return response()->json(['present'=>$present, 'absent'=>$absent]);
    }
}
