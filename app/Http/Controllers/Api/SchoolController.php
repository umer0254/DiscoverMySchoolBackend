<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;
use App\Models\School;

class SchoolController extends Controller
{
   


    public function registerSchool(Request $request)
{
    // Check if the user is authenticated
    if (!auth()->check()) {
        return response()->json(['message' => 'Authentication failed'], 401);
    }

    // Get the logged-in user's ID
    $loggedInUserId = auth()->user()->id;

    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'school_name' => 'required|string|max:255',
        'email' => 'required|email|unique:schools',
        'address' => 'required|string|max:255',
        'contact_number' => 'required|string|max:20',
        'admission_fee' => 'required|numeric',
        'tuition_fee' => 'required|numeric',
        'board' => 'required|in:Aga Khan,Cambridge,Federal,Matric', // Assuming these are the valid board options
        'area' => 'required|string|max:30',
        'city' => 'required|string|max:255',
        'school_image' => 'nullable|string', // Assuming school_image is a string representing the image URL
        'principal_name' => 'nullable|string|max:255',
        'principal_contact' => 'nullable|string|max:20',
        'principal_qualifications' => 'nullable|string|max:255',
        'principal_biography' => 'nullable|string',
        'mission_statement' => 'nullable|string',
        'school_history' => 'nullable|string',
        'facilities' => 'nullable|string',
        'extracurricular_activities' => 'nullable|string',
    ]);

    // If validation fails, return a response with validation errors
    if ($validator->fails()) {
        return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    }

    // Create a new school record
    $school = School::create([
        'school_name' => $request->input('school_name'),
        'email' => $request->input('email'),
        'address' => $request->input('address'),
        'contact_number' => $request->input('contact_number'),
        'admission_fee' => $request->input('admission_fee'),
        'tuition_fee' => $request->input('tuition_fee'),
        'board' => $request->input('board'),
        'city' => $request->input('city'),
        'area' => $request->input('area'),
        'school_image' => $request->input('school_image'),
        'principal_name' => $request->input('principal_name'),
        'principal_contact' => $request->input('principal_contact'),
        'principal_qualifications' => $request->input('principal_qualifications'),
        'principal_biography' => $request->input('principal_biography'),
        'mission_statement' => $request->input('mission_statement'),
        'school_history' => $request->input('school_history'),
        'facilities' => $request->input('facilities'),
        'extracurricular_activities' => $request->input('extracurricular_activities'),
        'user_id' => $loggedInUserId
    ]);

    // Return a response with success message and the created school data
    return response()->json(['message' => 'School registered successfully', 'school' => $school], 200);
}
 public function getSchoolDetails($id){
    if (!auth()->check()) {
        return response()->json(['message' => 'Authentication failed'], 401);
    }
    $school = School::find($id);

        // Checking if  school exists
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }


        return response()->json(['schools' => $school], 200);

 }
 public function applications($schoolId)
 {
     // Find the school
     $school = School::findOrFail($schoolId);

     // Retrieve applications for the school with student data
     $applications = $school->applications()->with('student')->get();

     return response()->json($applications);
 }
    
    
}


