<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;

class CityController extends Controller
{
    public function addCity(Request $request)
    {
    	$city = new City;
    	$city->name = $request->name;
    	$city->save();
    	return response()->json(['stauts'=>true]);
    }

    public function getCities(Request $request)
    {
    	$cities = City::all();
    	return response()->json(['cities'=>$cities]);
    }
}
