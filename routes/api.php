<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Auth\AuthController@login')->name('login');
    Route::post('register', 'Auth\AuthController@register');

    Route::post('studentregister', 'Auth\StudentAuthController@register');
    Route::post('studentlogin', 'Auth\StudentAuthController@login');

    Route::group(['middleware' => ['api', 'multiauth:student,api']], function () {
        Route::get('getstudent', 'Auth\StudentAuthController@user');
        Route::get('student/classes', 'Student\StudentController@getClasses');
        Route::post('student/sendmessage', 'Student\ChatController@sendMessage');
        Route::get('student/getmessages/{classId}', 'Student\ChatController@getMessages');
    });


    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('user', 'Auth\AuthController@user');
        Route::post('user/update', 'Auth\AuthController@update');
        Route::post('city/add', 'CityController@addCity');
        Route::get('cities', 'CityController@getCities');

        Route::post('school/add', 'SchoolController@addSchool');
        Route::get('schools', 'SchoolController@getSchools');
        Route::get('schools/checkforempty', 'SchoolController@checkForEmptySchool');
        Route::post('schools/addschooltouser', 'SchoolController@addSchoolToUser');

        Route::post('classes/add', 'MyClassController@addClass');
        Route::get('classes', 'MyClassController@getMyClasses');
        Route::get('classes/search/{name}', 'MyClassController@search');
        Route::get('myclass/{classId}', 'MyClassController@getAllClassData');


        Route::get('subjects', 'SubjectController@getMySubjects');
        Route::post('subject/add', 'SubjectController@addSubject');

        Route::post('lecture/add', 'LectureController@addLecture');
        Route::get('lecture/getsubjectsfromclass/{classId}', 'LectureController@getSubjectsFromClass');
        Route::get('lectures', 'LectureController@getMyLectures');

        Route::post('student/add', 'StudentController@addNewStudent');
        Route::get('students/{classId}', 'StudentController@getStudentsForClass');
        Route::get('students', 'StudentController@getAllStudents');
        Route::get('studentsids/{classId}', 'StudentController@getClassStudentsIds');
        Route::post('addstudentstoclass', 'StudentController@addStudentsToClass');
        Route::get('student/getstudentattendanceforclass/{classId}/{studentId}', 'StudentController@getStudentAttendanceForClass');
        Route::get('student/getstudentgradesforclass/{classId}/{studentId}', 'StudentController@getStudentGradesForClass');

        Route::post('attendance/getdaylectures', 'AttendanceController@getDayLectures');
        Route::post('attendance/addattendance', 'AttendanceController@addAttendance');
        Route::get('attendance/getattendancetakepage/{lectureId}', 'AttendanceController@getAttendanceTakePage');

        Route::post('sendmessage', 'ChatController@sendMessage');
        Route::get('getmessages/{classId}', 'ChatController@getMessages');
        Route::get('deletemessage/{messageId}', 'ChatController@deleteMessage');

        Route::post('grades/add', 'GradeController@addGrade');
        Route::get('getgradesforgradeHome/{classId}', 'GradeController@getGradesForGradeHome');
        Route::get('getstudentgrade/{studentId}/{gradeId}', 'GradeController@getStudentGrade');
        Route::post('editstudentgrade', 'GradeController@editStudentGrade');
        Route::post('addstudentgrade', 'GradeController@addStudentGrade');

        Route::post('assignment/add', 'AssignmentController@addAssignment');
        Route::get('assignments/{classId}', 'AssignmentController@getAllAssignments');

    });
});
