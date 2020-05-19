<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Subject;
use App\MyClass;
use Illuminate\Support\Facades\Auth;
use Validator;

class StudentController extends Controller
{
	public function addNewStudent(Request $request)
	{
        $validator = Validator::make($request->all(), [
                  'name'        => 'required',
                  'password'    => 'required|min:6',
                  'email'       => 'email|required|unique:students',
                  'phone'       => 'required|unique:students',
        ]);

        if ($validator->fails()) {
           return response()->json(['status'=>false, 'error'=>$validator->errors()], 401);
        }

    	$school_id = Auth::user()->school_id;
		$student = new Student;
		$student->name = $request->name;
		$student->email = $request->email;
		$student->phone = $request->phone;
		$student->password = bcrypt($request->password);
		$student->school_id = $school_id;
        $image = $request->image;
        if ($image == null) {
			$imageUrl = 'student.png';
			$student->image = $imageUrl;
			$student->save();
			return response()->json(['status'=>true]);
        }
        else {
			$data = base64_decode($image);
			$imageUrl = "student-".time().".png";
			$path = public_path().'/img/students/' . $imageUrl;
			$student->image = $imageUrl;
			$student->save();
			file_put_contents($path, $data);
			return response()->json(['status'=>true]);
        }
	}

	public function getStudentsForClass($classId)
	{
		$myClass = MyClass::find($classId);
		$students = $myClass->students;
		return $students;
	}

	public function getAllStudents()
	{
    	$school = Auth::user()->school;
		$students = $school->students;
		return $students;
	}

	public function getClassStudentsIds($classId)
	{
		$myClass = MyClass::find($classId);
		$students = $myClass->students->pluck('id')->toArray();
		return $students;
	}

	public function addStudentsToClass(Request $request)
	{
		$studentsIds = json_decode($request->selected_students);
		$class = MyClass::find($request->class_id);
		$class->students()->sync($studentsIds);
		return response()->json(['status'=>true]);
	}

	public function getStudentAttendanceForClass($classId, $studentId)
	{
		$lectures = MyClass::find($classId)->lectures;
		$attendance = array();
		foreach ($lectures as $lecture) {
			$test = $lecture->students()->wherePivot("student_id", $studentId)->first();
			$test->lecture = $lecture;
			array_push($attendance, $test);

		}
		return $attendance;
	}

	public function getStudentGradesForClass($classId, $studentId)
	{
		$grades = MyClass::find($classId)->grades;
		$my_grades = array();
		foreach ($grades as $grade) {
			$test = $grade->students()->wherePivot("student_id", $studentId)->first();
			$test['mygrade'] = $grade;
			array_push($my_grades, $test);
		}
		return $my_grades;
	}
}
