<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lecture;
use App\Subject;

class LectureController extends Controller
{
    public function addLecture(Request $request)
    {
		$data = base64_decode($request->file);
		$file = $request->name . time() . "." . explode('.', $request->ext)[1];
		$path = public_path().'/files/lectures/' . $file;
		file_put_contents($path, $data);
		$lecture = new Lecture;
		$lecture->name = $request->name;
		$lecture->description = $request->description;
		$lecture->file = $file;
        $lecture->subject_id = $request->subject_id;
        $lecture->lectureDate = $request->date;
		$lecture->lectureTime = $request->time;
		$lecture->user_id = Auth::user()->id;
		$lecture->save();
    	return response()->json(['status'=>true]);
    }

    public function getSubjectsFromClass($classId)
    {
    	$subjects = Subject::where('class_id', $classId)->get();
    	return $subjects;
    }

    public function getMyLectures()
    {
    	$user = Auth::user();
    	$lectures = $user->lectures;
    	return $lectures;
    }
}
