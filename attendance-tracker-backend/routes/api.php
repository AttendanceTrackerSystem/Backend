<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthControllerTec;
use App\Models\Department;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/departments', function () {
    return Department::all();
});

Route::get('/departments/{id}/subjects', function ($id) {
    $department = Department::with('subjects')->findOrFail($id);
    return $department->subjects;
});


Route::post('/logintech', [AuthControllerTec::class, 'login']);


Route::get('/student/{id}', [AuthControllerTec::class, 'show']);

use App\Http\Controllers\TeacherController;

Route::get('/teachers', [TeacherController::class, 'index']); // List all teachers
Route::get('/teachers/{id}', [TeacherController::class, 'show']); // Single teacher detail



use App\Http\Controllers\ClassController;

Route::get('/classes', [ClassController::class, 'getClassesByDepartmentAndSubject']);
