<?php

// app/Models/AttendanceRecord.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'is_present',
        'rating',
        'comment',
        'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_number');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class); // Replace with actual Class model if named differently
    }
}