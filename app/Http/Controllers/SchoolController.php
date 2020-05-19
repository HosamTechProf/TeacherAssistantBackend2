<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\User;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    public function addSchool(Request $request)
    {
    	$school = New School;
    	$school->name = $request->name;
    	$school->address = $request->address;
    	$school->city_id = $request->city_id;
    	$school->save();
    	return response()->json(['status'=>true]);
    }

    public function getSchools()
    {
    	$schools = School::all();
    	return response()->json(['schools'=>$schools]);
    }

    public function checkForEmptySchool()
    {
    	$user = Auth::user();
    	if ($user->school_id == null) {
    		return response()->json(['status'=>false]);
    	}
    	return response()->json(['status'=>true]);
    }

    public function addSchoolToUser(Request $request)
    {
    	$user = Auth::user();
    	$user->school_id = $request->school_id;
    	$user->save();
    	return response()->json(['status'=>true]);
    }
}
