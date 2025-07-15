<?php
// app/Http/Controllers/AttendanceController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;

class AttendanceController extends Controller
{
    public function submitComment(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,student_number',
            'class_id' => 'required|exists:classes,id',
            'comment' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'date' => 'required|date',
        ]);
        $existing = AttendanceRecord::where('student_id', $validated['student_id'])
            ->where('class_id', $validated['class_id'])
            ->where('date', $validated['date'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Attendance already submitted.'], 409);
        }

        try {
    $record = AttendanceRecord::create([
        'student_id' => $validated['student_id'],
        'class_id' => $validated['class_id'],
        'is_present' => true,
        'rating' => $validated['rating'] ?? null,
        'comment' => $validated['comment'] ?? null,
        'date' => $validated['date'],
    ]);

    return response()->json(['message' => 'Attendance submitted successfully', 'data' => $record], 201);

} catch (\Exception $e) {
    \Log::error('Attendance submission failed: ' . $e->getMessage());
    return response()->json(['message' => 'Internal Server Error'], 500);
}

    }
}

 function getAttendanceStatistics(Request $request)
{
    $classId = $request->query('class_id');
    $subjectId = $request->query('subject_id');
    $departmentId = $request->query('department_id');

    // Total students in subject + department
    $totalStudents = StudentSubject::where('subject_id', $subjectId)
                        ->whereHas('student', function ($query) use ($departmentId) {
                            $query->where('dept_id', $departmentId);
                        })->count();

    // Present count for the selected class
    $presentCount = AttendanceRecord::where('class_id', $classId)
                        ->where('is_present', true)
                        ->count();

    return response()->json([
        'total_students' => $totalStudents,
        'present_students' => $presentCount,
        'attendance_percentage' => $totalStudents > 0 
            ? round(($presentCount / $totalStudents) * 100, 2) 
            : 0
    ]);
}









