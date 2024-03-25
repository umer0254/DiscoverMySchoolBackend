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
    public function getAllSchools(Request $request)
    {
       

    // Get search query parameters
    $name = $request->input('school_name');
    $area = $request->input('area');
    $max_tuition_fee = $request->input('max_tuition_fee');
    $city = $request->input('city');
    $board = $request->input('board');

    // Start with a base query of all schools where is_approved is true
    $query = School::where('is_approved', true);

    // Apply filters
    if ($name) {
        $query->where('school_name', 'LIKE', "%$name%");
    }
    if ($city) {
        $query->where('city', $city);
    }
    if ($board) {
        $query->where('board', $board);
    }
    if ($area) {
        $query->where('area', $area);
    }
    if ($max_tuition_fee) {
        $query->where('tuition_fee', '<=', $max_tuition_fee);
    }

    // Get the result
    $schools = $query->get();

    return response()->json(['schools' => $schools], 200);


    }

    public function getUnapprovedSchools()
    {
        // $user = Auth::user();
        // if (!$user || $user->user_type !== 'Admin') {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }
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
            'is_approved' => true,
            'admin_id' => $userId,
        ]);
        $school->save();
        // dd($school->is_approved);

        return response()->json(['message' => 'School approved successfully'], 200);
    }

    public function rejectSchool($schoolId)
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
            'is_approved' => false,
            'admin_id' => $userId,
        ]);
        $school->save();
        // dd($school->is_approved);

        return response()->json(['message' => 'School Approval Rejected successfully'], 200);

    }

    public function deleteUsers($userId)
    {
        $user = Auth::user();
        if (!$user || $user->user_type !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 404);
        }
        $user_ = User::find($userId);
        $user_->delete();
        return response()->json(['message' => 'User Deleted Succesfully'], 200);
    }


}
