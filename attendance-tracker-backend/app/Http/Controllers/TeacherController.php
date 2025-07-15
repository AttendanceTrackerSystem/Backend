<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{

    // Fetch single teacher by ID
    public function show($id)
    {
        $teacher = Teacher::with('department')->find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        return response()->json($teacher);
    }

    // app/Http/Controllers/TeacherController.php
public function index(Request $request)
{
    $departmentId = $request->query('department_id');
    $subjectId = $request->query('subject_id');

    $query = Teacher::query();

    if ($departmentId) {
        $query->where('department_id', $departmentId);
    }

    if ($subjectId) {
        $query->where('subject_id', $subjectId);
    }

    $teachers = $query->get();

    return response()->json($teachers);
}

}
