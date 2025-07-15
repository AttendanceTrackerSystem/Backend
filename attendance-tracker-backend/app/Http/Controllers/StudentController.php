<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Subject;

class StudentController extends Controller
{
    // Get student info by student number (or id)
    public function getStudent(Request $request, $studentNumber)
    {
        $student = Student::where('student_number', $studentNumber)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        return response()->json($student);
    }

    // Get all departments (for dropdown)
    public function getDepartments()
    {
        $departments = Department::all();
        return response()->json($departments);  // Return plain array for React
    }

    // Get subjects filtered by department_id query param
    public function getSubjects(Request $request)
    {
        $departmentId = $request->query('department_id');

        if (!$departmentId) {
            return response()->json(['error' => 'department_id query param is required'], 400);
        }

        $subjects = Subject::where('department_id', $departmentId)->get();
        return response()->json($subjects);  // Return plain array for React
    }

    // Get subject details by id, including teacher and lectures
    public function getSubjectDetails($id)
    {
        $subject = Subject::with(['teacher', 'lectures'])->find($id);

        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        return response()->json($subject);
    }
}
