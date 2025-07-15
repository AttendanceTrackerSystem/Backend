<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Subject;

class StudentController extends Controller
{
    public function getStudent(Request $request, $studentNumber)
    {
        $student = Student::where('student_number', $studentNumber)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        return response()->json($student);
    }

    public function getDepartments()
    {
        $departments = Department::all();
        return response()->json($departments);  
    }

    public function getSubjects(Request $request)
    {
        $departmentId = $request->query('department_id');

        if (!$departmentId) {
            return response()->json(['error' => 'department_id query param is required'], 400);
        }

        $subjects = Subject::where('department_id', $departmentId)->get();
        return response()->json($subjects);  
    }
    public function getSubjectDetails($id)
    {
        $subject = Subject::with(['teacher', 'lectures'])->find($id);

        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        return response()->json($subject);
    }
}
