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
    public function getAllSchools()
    {
        $schools = School::all();

        return response()->json(['schools' => $schools], 200);
    }


    public function registerSchool(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Authentication failed'], 401);
        }

        // Get the logged-in user's ID
        $loggedInUserId = auth()->user()->id;
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'email' => 'required|email|unique:schools', // Assuming 'schools' is the table name for your School model
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'admission_fee' => 'required|numeric',
            'tuition_fee' => 'required|numeric',
            'school_type' => 'required|string|max:255',
            // 'user_id' =>   $loggedInUserId ,
            // 'admin_id' => 'required|exists:users,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return a response with validation errors
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
            'school_type' => $request->input('school_type'),
            'user_id' => $loggedInUserId
        ]);

        // Optionally, you can perform additional actions here, such as sending email notifications, etc.

        // Return a response indicating success
        return response()->json(['message' => 'School registered successfully', 'school' => $school], 200);
    }

    public function getUnapprovedSchools()
    {
        $unapprovedSchools = School::where('is_approved', false)->get();

        return response()->json($unapprovedSchools);
    }

    public function approveSchool($schoolId)
    {
        // Check if the loggedin user is admin
        $user = Auth::user();
        if (!$user || $user->user_type !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
    
        $school = School::find($schoolId);
    
        // Checking if  school exists
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }
    
        // Updating school approval and admin id which admin has approved
        $userId = $user->id;
        $school->update([
            'is_approved' =>true,
            'admin_id' => $userId,
        ]);
        $school->save();
        // dd($school->is_approved);
    
        return response()->json(['message' => 'School approved successfully']);
    }
    
}


