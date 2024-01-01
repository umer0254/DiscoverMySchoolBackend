<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;
use App\Models\School;

class AdminController extends Controller
{
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