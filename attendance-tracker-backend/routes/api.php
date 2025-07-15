<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Department;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/departments', function () {
    return Department::all();
});
Route::get('/departments/{id}/subjects', function ($id) {
    $department = Department::with('subjects')->findOrFail($id);
    return $department->subjects;
});