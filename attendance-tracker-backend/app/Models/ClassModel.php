<?php

// app/Models/ClassModel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'department_id',
        'subject_id',
        'teacher_id',
        'class_name',
        'hall_number',
        'date',
        'day',
        'start_time',
        'end_time',
        'week',
        'description',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}

