<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'name',
    ];

    // One Department has many Students
    public function students()
    {
        return $this->hasMany(Student::class, 'dept_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'department_subject', 'department_id', 'subject_id');
    }
}
