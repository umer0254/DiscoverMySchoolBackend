<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
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
    public function getSchoolDetails($id)
    {
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
    public function applications()
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json(['message' => 'Authentication failed'], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::user();

            // Fetch the school associated with the logged-in user
            $school = School::where('user_id', $user->id)->first();

            // Check if school exists
            if (!$school) {
                return response()->json(['message' => 'School not found'], 404);
            }

            // Retrieve pending applications for the school with student data
            $applications = $school->applications()->with('student')->where('status', 'pending')->get();

            // Check if pending applications exist
            if ($applications->isEmpty()) {
                return response()->json(['message' => 'No pending applications found for this school'], 404);
            }

            // Return the pending applications
            return response()->json($applications);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
    public function approvedapplications()
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json(['message' => 'Authentication failed'], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::user();

            // Fetch the school associated with the logged-in user
            $school = School::where('user_id', $user->id)->first();

            // Check if school exists
            if (!$school) {
                return response()->json(['message' => 'School not found'], 404);
            }

            // Retrieve pending applications for the school with student data
            $applications = $school->applications()->with('student')->where('status', 'approved')->get();

            // Check if pending applications exist
            if ($applications->isEmpty()) {
                return response()->json(['message' => 'No approved applications found for this school'], 404);
            }

            // Return the pending applications
            return response()->json($applications);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    public function giveremarks(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Authentication failed'], 401);
        }
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $application = Application::findOrFail($id);

        $application->status = $request->status;
        $application->remarks = $request->remarks;
        $application->save();

        return response()->json(['message' => 'Application status updated successfully'], 200);
    }


    public function getProfile(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['message' => 'Authentication failed'], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::user();
            

            // Fetch the school associated with the logged-in user
            $school = School::where('user_id', $user->id)->first();

            // Check if school exists
            if (!$school) {
                return response()->json(['message' => 'School not found'], 404);
            }

            // Return the school profile data
            return response()->json($school);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    public function updateProfile(Request $request)
    {
        try {
            // Retrieve the authenticated user
            if (!Auth::check()) {
                return response()->json(['message' => 'Authentication failed'], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::user();

            // Fetch the school associated with the logged-in user
            $school = School::where('user_id', $user->id)->first();

            // Check if school exists
            if (!$school) {
                return response()->json(['message' => 'School not found'], 404);
            }

            // Validate the request data
            $request->validate([
                'admission_fee' => 'numeric|nullable',
                'admission_status' => 'in:Open,Closed|nullable',
                'contact_number' => 'string|nullable',
                'tuition_fee' => 'numeric|nullable',
                'principal_name' => 'string|nullable',
                'principal_biography' => 'string|nullable',
                'extracurricular_activities' => 'string|nullable',

            ]);

            // Update the school profile data
            $school->update($request->all());

            // Return success response
            return response()->json(['message' => 'School profile updated successfully']);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
    public function checkUserSchool(Request $request)
    {
        $user = $request->user(); // Get the logged-in user

        // Check if the user has a school
        $school = School::where('user_id', $user->id)->first();
    
        if ($school) {
            return response()->json(['has_school' => true, 'school' => $school]);
        } else {
            return response()->json(['has_school' => false]);
        }
    }
}







