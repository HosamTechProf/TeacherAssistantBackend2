<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grade;
use App\MyClass;
use App\Student;

class GradeController extends Controller
{
    public function addGrade(Request $request)
    {
    	$grade = new Grade;
    	$grade->title = $request->title;
    	$grade->max_grade = $request->max_grade;
    	$grade->description = $request->description;
    	$grade->class_id = $request->class_id;
    	$grade->save();

    	return response()->json(['status'=>true]);
    }

    public function getGradesForGradeHome($classId)
    {
    	$myClass = MyClass::find($classId);
    	$students = $myClass->students;
    	$grades = $myClass->grades;
    	foreach ($students as $student) {
    		$student->gradess = $grades;
    	}
    	return response()->json(['students'=>$students, 'grades'=>$grades]);
    }

    public function getStudentGrade($studentId, $gradeId)
    {
    	$student = Student::find($studentId);
    	$grade = $student->grades()->wherePivot('grade_id', $gradeId)->get();
    	return $grade;
    }

    public function editStudentGrade(Request $request)
    {
    	$student_id = $request->student_id;
    	$grade_id = $request->grade_id;
    	$grade = $request->grade;
    	$student = Student::find($student_id);
		$student->grades()->updateExistingPivot($grade_id, ['grade' => $grade]);
		return response()->json(['status'=>true]);
    }

    public function addStudentGrade(Request $request)
    {
    	$student_id = $request->student_id;
    	$grade_id = $request->grade_id;
    	$grade = $request->grade;
    	$student = Student::find($student_id);

    	if ($student->grades()->wherePivot('grade_id', $grade_id)->count() < 1) {
			$student->grades()->attach($grade_id, ['grade' => $grade]);
    	}else{
    		$student->grades()->updateExistingPivot($grade_id, ['grade' => $grade]);
    	}
    	
		return response()->json(['status'=>true]);
    }
}
