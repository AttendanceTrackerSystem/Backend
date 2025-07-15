<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'student_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $student = Student::with('department')->where('student_number', $request->student_number)->first();


        if (!$student || $request->password !== $student->password) {
    return response()->json(['message' => 'Invalid credentials'], 401);
}

        return response()->json([
            'student' => [
                'student_number' => $student->student_number,
                'full_name' => $student->full_name,
                'email' => $student->email,
                'dept_id' => $student->dept_id,
                'department' => $student->department ? $student->department->name : null,
            ],
            'message' => 'Login successful',
        ]);
    }
}
