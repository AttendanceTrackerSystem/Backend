<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;

class ClassController extends Controller
{
    public function getClassesByDepartmentAndSubject(Request $request)
    {
        $departmentId = $request->query('department_id');
        $subjectId = $request->query('subject_id');

        $classes = ClassModel::with(['teacher', 'department', 'subject'])
            ->where('department_id', $departmentId)
            ->where('subject_id', $subjectId)
            ->get();

        return response()->json($classes);
    }
}
