<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class AuthControllerTec extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $teacher = Teacher::where('teacher_id', $request->teacher_id)->first();

        if (!$teacher || $request->password !== $teacher->password) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'teacher' => [
                'teacher_id' => $teacher->teacher_id,
                'teacher_name' => $teacher->teacher_name,
                'email' => $teacher->email,
                'phone_number' => $teacher->phone_number,
                'department_id' => $teacher->department_id,
                'subject_id' => $teacher->subject_id,
            ],
            'message' => 'Login successful',
        ]);
    }
}
