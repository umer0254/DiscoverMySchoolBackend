<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/userlogin', [AuthController::class, 'login'])->name('userlogin');
Route::post('/userregistrattion', [AuthController::class, 'register'])->name('userlogin');
Route::post('/getAllSchools', [AdminController::class, 'getAllSchools'])->name('getAllSchools');
Route::get('/getUnapprovedSchools', [AdminController::class, 'getUnapprovedSchools'])->name('getUnapprovedSchools');
// Route::get('/getapplication/{id}', [SchoolController::class, 'applications'])->name('getapplication');
Route::get('/getstudentapplication/{id}', [StudentController::class, 'studentapplications'])->name('getstudentapplication');
// Route::get('/getSchoolDetails/{id}', [SchoolController::class, 'getSchoolDetails'])->name('getSchoolDetails');



//api with authentication

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::get('/getAllSchools', [SchoolController::class, 'getAllSchools']);
    Route::get('/my-students', [StudentController::class, 'getMyStudents']);
    Route::get('/getSchoolDetails/{id}', [SchoolController::class, 'getSchoolDetails']);
    Route::get('/getschoollapplication', [SchoolController::class, 'applications']);
    Route::get('/approvedapplications', [SchoolController::class, 'approvedapplications']);
    Route::get('/getprofile', [SchoolController::class, 'getProfile']);
    Route::get('/checkUserSchool', [SchoolController::class, 'checkUserSchool']);
    // Route::get('/getUnapprovedSchools', [AdminController::class, 'getUnapprovedSchools']);
    Route::post('/studentregistration', [StudentController::class, 'registerStudent']);
    Route::post('/schoolregistration', [SchoolController::class, 'registerSchool']);
    Route::put('/approveSchool/{schoolId}', [AdminController::class, 'approveSchool']);
    Route::put('/giveremarks/{id}', [SchoolController::class, 'giveremarks']);
    Route::put('/updateProfile', [SchoolController::class, 'updateProfile']);
    Route::put('/unapproveSchool/{schoolId}', [AdminController::class, 'unapproveSchool']);
    Route::post('/apply', [StudentController::class, 'apply']);
 
});
