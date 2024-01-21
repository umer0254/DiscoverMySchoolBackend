<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;
use App\Models\School;
use App\Models\Student;

class AuthController extends Controller
{

    public function apiResponse($success, $message, $data, $statusCode)
    {

        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
                'phone_number' => 'required',
                'last_name' => 'required',
                'user_type' => 'required'
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return self::apiResponse(false, $errors, [], 422);
            }
    
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
    
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->first_name;
    
            return self::apiResponse(true, "User Registered Successfully", $success, 200);
    
        } catch (\Throwable $th) {
            return self::apiResponse(false, $th->getMessage(), [], 422);
        }
    }
    
    
    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::User();
                $user->tokens()->delete();
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->first_name;
                $success['user_type'] = $user->user_type;
            } else {
                return self::apiResponse(false, "User Login Failed", [], 422);
            }
    
            // Add this line to revoke tokens, whether the login attempt was successful or not
            // $user->tokens()->delete();
    
            return self::apiResponse(true, "User Login Successful", $success, 200);
        } catch (\Throwable $th) {
            return self::apiResponse(false, $th->getMessage(), [], 422);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
    


}
