<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;
use App\Models\Student;

class StudentController extends Controller
{
    public function getMyStudents()
    {
        if (!Auth::check()) {
            // Return a response indicating authentication failure
            return response()->json(['message' => 'Authentication failed'], 401);
        }

        $user = Auth::user();

        // Fetch the students associated with the logged-in user
        $students = Student::where('user_id', $user->id)->get();

        return response()->json(['students' => $students], 200);
    }

    public function registerStudent(Request $request)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'Authentication failed'], 401);
        }

        // Get the logged-in user's ID
        $loggedInUserId = auth()->user()->id;

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'father_occupation' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'mother_occupation' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'father_cnic' => 'required|string|max:255',
            'student_cnic' => 'required|string|max:255',
            'applying_for_class' => 'required|string|max:255',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return a response with validation errors
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        // Create a new student record with the user_id set to the logged-in user's ID
        $student = Student::create([
            'user_id' => $loggedInUserId,
            'student_name' => $request->input('student_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'father_occupation' => $request->input('father_occupation'),
            'father_name' => $request->input('father_name'),
            'mother_name' => $request->input('mother_name'),
            'mother_occupation' => $request->input('mother_occupation'),
            'address' => $request->input('address'),
            'father_cnic' => $request->input('father_cnic'),
            'student_cnic' => $request->input('student_cnic'),
            'applying_for_class' => $request->input('applying_for_class'),
        ]);

        // Optionally, you can perform additional actions here

        // Return a response indicating success
        return response()->json(['message' => 'Student registered successfully', 'student' => $student], 200);
    }
}
