<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('teachers', [TeacherController::class, 'index']);
Route::post('add-teacher', [TeacherController::class, 'store']);
Route::post('edit-teacher/{id}', [TeacherController::class, 'update']);
Route::post('activate-teacher/{id}', [TeacherController::class, 'activateTeacher']);
Route::post('deactivate-teacher/{id}', [TeacherController::class, 'deactivateTeacher']);


Route::get('schools', [SchoolController::class, 'index']);
Route::post('add-school', [SchoolController::class, 'store']);
Route::post('edit-school/{id}', [SchoolController::class, 'update']);
Route::post('activateSchool/{id}', [SchoolController::class, 'activateSchool']);
Route::post('deactivateSchool/{id}', [SchoolController::class, 'deactivateSchool']);


Route::get('coachings', [CoachingController::class, 'index']);
Route::post('add-coaching', [CoachingController::class, 'store']);
Route::post('edit-coaching/{id}', [CoachingController::class, 'update']);
Route::post('activateCoaching/{id}', [CoachingController::class, 'activateCoaching']);
Route::post('deactivateCoaching/{id}', [CoachingController::class, 'deactivateCoaching']);


Route::get('students', [UserController::class, 'index']);
Route::post('add-student', [UserController::class, 'store']);
Route::post('edit-student/{id}', [UserController::class, 'update']);
Route::post('activateStudent/{id}', [UserController::class, 'activateCoaching']);
Route::post('deactivateStudent/{id}', [UserController::class, 'deactivateCoaching']);


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
