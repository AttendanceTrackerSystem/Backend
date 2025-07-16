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


use App\Http\Controllers\AttendanceController;
Route::post('/submit_attendance', [AttendanceController::class, 'submitComment']);



use App\Http\Controllers\TechAttendanceController;

Route::get('/teacher/class-stats', [TechAttendanceController::class, 'getTeacherClassStats']);


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/teacher/classes', function (Request $request) {
    $teacherId = $request->query('teacher_id');

    $classes = DB::table('classes')
        ->join('subjects', 'classes.subject_id', '=', 'subjects.id')
        ->where('classes.teacher_id', $teacherId)
        ->orderBy('classes.date', 'desc')
        ->select('classes.id', 'classes.class_name', 'subjects.name as subject', 'classes.date', 'classes.start_time', 'classes.end_time')
        ->get();

    return response()->json($classes);
});
Route::get('/teacher/class/{classId}/students', function ($classId) {
    $class = \App\Models\ClassModel::with('subject', 'department')->findOrFail($classId);

    $students = DB::table('student_subjects')
        ->join('students', 'student_subjects.student_id', '=', 'students.id')
        ->where('student_subjects.subject_id', $class->subject_id)
        ->where('students.dept_id', $class->department_id)
        ->select('students.id as student_id', 'students.full_name')
        ->get();

    return response()->json($students);
});



Route::middleware('api')->group(function () {

    Route::get('/teacher/classes', function (Request $request) {
        $teacherId = $request->query('teacher_id');

        $classes = DB::table('classes')
            ->join('subjects', 'classes.subject_id', '=', 'subjects.id')
            ->where('classes.teacher_id', $teacherId)
            ->orderBy('classes.date', 'desc')
            ->select('classes.id', 'classes.class_name', 'subjects.name as subject', 'classes.date', 'classes.start_time', 'classes.end_time')
            ->get();

        return response()->json($classes);
    });
    Route::get('/teacher/class/{classId}/students', function ($classId) {
        $students = DB::table('student_subjects')
            ->join('students', 'student_subjects.student_id', '=', 'students.id')
            ->where('student_subjects.class_id', $classId)
            ->select('students.id as student_id', 'students.full_name')
            ->get();

        return response()->json($students);
    });
    Route::get('/teacher/class/{classId}/attendance-count', function ($classId) {
        $presentCount = DB::table('attendance_records')
            ->where('class_id', $classId)
            ->where('is_present', true)
            ->count();

        return response()->json(['present_count' => $presentCount]);
    });

    Route::post('/teacher/submit-attendance', function (Request $request) {
        $data = $request->all();

        foreach ($data as $record) {
            DB::table('attendance_records')->updateOrInsert(
                [
                    'student_id' => $record['student_id'],
                    'class_id' => $record['class_id'],
                    'date' => $record['date'],
                ],
                [
                    'is_present' => $record['is_present'],
                    'updated_at' => now(),
                ]
            );
        }
        return response()->json(['message' => 'Attendance submitted successfully']);
    });

    Route::get('/teacher/attendance-report', function (Request $request) {
        $subjectId = $request->query('subject_id');
        $from = $request->query('from');
        $to = $request->query('to');

        $students = DB::table('student_subjects')
            ->join('students', 'student_subjects.student_id', '=', 'students.id')
            ->where('student_subjects.subject_id', $subjectId)
            ->select('students.id', 'students.full_name')
            ->get();

        $report = [];
        foreach ($students as $student) {
            $total = DB::table('attendance_records')
                ->where('student_id', $student->id)
                ->whereBetween('date', [$from, $to])
                ->whereIn('class_id', function ($query) use ($subjectId) {
                    $query->select('id')
                        ->from('classes')
                        ->where('subject_id', $subjectId);
                })
                ->count();

            $present = DB::table('attendance_records')
                ->where('student_id', $student->id)
                ->where('is_present', true)
                ->whereBetween('date', [$from, $to])
                ->whereIn('class_id', function ($query) use ($subjectId) {
                    $query->select('id')
                        ->from('classes')
                        ->where('subject_id', $subjectId);
                })
                ->count();

            $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;

            $report[] = [
                'full_name' => $student->full_name,
                'attendance_percentage' => $percentage,
            ];
        }

        return response()->json($report);
    });

});



Route::get('/teacher/class/{classId}/absent-students', [AttendanceController::class, 'getAbsentStudents']);
Route::get('/present-students', [AttendanceController::class, 'getPresentStudents']);
