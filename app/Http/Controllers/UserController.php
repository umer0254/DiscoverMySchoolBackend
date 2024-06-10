<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;
use App\Models\School;


class UserController extends Controller
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
                return self::apiResponse(false, $validator->errors(), [
                    "email" => "test@gmail.com",
                    "password" => "123",
                    "c_password" => "123",
                    "first_name" => "Dell",
                    "last_name" => "Latitude",
                    "phone_number" => "03002119781",
                    "user_type" => "school"

                ], 422);
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
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->first_name;
                return self::apiResponse(true, "User Login Successfull", $success, 200);
            } else {
                return self::apiResponse(false, "User Login Failed", [], 422);

            }
        } catch (\Throwable $th) {
            return self::apiResponse(false, $th->getMessage(), $success, 422);
        }


    }


    //
}
