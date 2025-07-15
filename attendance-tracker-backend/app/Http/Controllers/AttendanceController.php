<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

  public function getAbsentStudents($classId)
{
    $date = request()->query('date', now()->toDateString());

    $absentStudents = \DB::table('attendance_records')
        ->join('students', 'attendance_records.student_id', '=', 'students.student_number') // Assuming students table and student_number key
        ->select('students.student_number', 'students.full_name')
        ->where('attendance_records.class_id', $classId)
        ->where('attendance_records.date', $date)
        ->where('attendance_records.is_present', 0)
        ->get();

    return response()->json($absentStudents);
}


}

