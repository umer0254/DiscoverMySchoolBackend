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
// Route::middleware('auth:sanctum')->get('/getAllSchools', [SchoolController::class, 'getAllSchools']);
Route::middleware('auth:sanctum')->get('/my-students', [StudentController::class, 'getMyStudents']);
// Route::middleware('auth:sanctum')->get('/getUnapprovedSchools', [AdminController::class, 'getUnapprovedSchools']);
Route::middleware('auth:sanctum')->post('/studentregistration', [StudentController::class, 'registerStudent']);
Route::middleware('auth:sanctum')->post('/schoolregistration', [SchoolController::class, 'registerSchool']);
Route::middleware('auth:sanctum')->put('/approveSchool/{schoolId}', [AdminController::class, 'approveSchool']);