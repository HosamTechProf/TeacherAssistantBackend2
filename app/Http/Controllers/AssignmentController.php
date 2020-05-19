<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Assignment;
use App\MyClass;

class AssignmentController extends Controller
{
    public function addAssignment(Request $request)
    {
		$data = base64_decode($request->file);
		$file = $request->title . time() . "." . explode('.', $request->ext)[1];
		$path = public_path().'/files/assignments/' . $file;
		file_put_contents($path, $data);

    	$assignment = new Assignment;
        $assignment->title = $request->title;
        $assignment->class_id = $request->class_id;
    	$assignment->file = $file;
        $assignment->assignmentDate = $request->assignmentDate;
    	$assignment->description = $request->description;
        $assignment->save();
        return response()->json(['status'=>true]);
    }

    public function getAllAssignments($classId)
    {
    	$myClass = MyClass::find($classId);
        $assignments = $myClass->assignments;
        return $assignments;
    }
}
