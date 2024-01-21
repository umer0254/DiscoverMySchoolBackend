<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\StudentController;

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
Route::get('/getAllSchools', [AdminController::class, 'getAllSchools'])->name('getAllSchools');
Route::get('/getUnapprovedSchools', [AdminController::class, 'getUnapprovedSchools'])->name('getUnapprovedSchools');



//api with authentication

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::get('/getAllSchools', [SchoolController::class, 'getAllSchools']);
    Route::get('/my-students', [StudentController::class, 'getMyStudents']);
    // Route::get('/getUnapprovedSchools', [AdminController::class, 'getUnapprovedSchools']);
    Route::post('/studentregistration', [StudentController::class, 'registerStudent']);
    Route::post('/schoolregistration', [SchoolController::class, 'registerSchool']);
    Route::put('/approveSchool/{schoolId}', [AdminController::class, 'approveSchool']);
    Route::post('/approveSchool/{schoolId}', [AdminController::class, 'approveSchool']);
 
});
