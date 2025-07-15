<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function show($id)
    {
        $teacher = Teacher::with('department')->find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        return response()->json($teacher);
    }

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









    public function getStudents($classId)
    {
        // Assuming you have a students table linked to classes via a pivot or foreign key
        $students = DB::table('students')
            ->join('student_classes', 'students.student_number', '=', 'student_classes.student_number')
            ->where('student_classes.class_id', $classId)
            ->select('students.student_number as student_id', 'students.full_name')
            ->get();

        return response()->json($students);
    }

   

public function getAbsentStudents(Request $request, $classId)
{
    $date = $request->query('date');

    if (!$date) {
        return response()->json(['message' => 'Date is required'], 400);
    }

    $absentStudents = DB::table('attendance_records')
        ->join('students', 'attendance_records.student_id', '=', 'students.student_number')
        ->where('attendance_records.class_id', $classId)
        ->where('attendance_records.date', $date)
        ->where('attendance_records.is_present', 0)
        ->select('students.student_number', 'students.full_name')
        ->distinct()
        ->get();

    return response()->json($absentStudents);
}
  


public function getSummary($classId)
{
    $date = request()->query('date', now()->toDateString()); // optional: support `?date=2025-07-15`

    $presentCount = DB::table('attendance_records')
        ->where('class_id', $classId)
        ->where('date', $date)
        ->where('is_present', 1)
        ->count();

    $absentCount = DB::table('attendance_records')
        ->where('class_id', $classId)
        ->where('date', $date)
        ->where('is_present', 0)
        ->count();

    $total = $presentCount + $absentCount;
    $percentage = $total > 0 ? round(($presentCount / $total) * 100, 2) : 0;

    return response()->json([
        'present_count' => $presentCount,
        'absent_count' => $absentCount,
        'present_percentage' => $percentage,
    ]);
}
}
