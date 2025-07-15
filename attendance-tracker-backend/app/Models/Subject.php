<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'name',
    ];

    // A Subject can belong to many Departments (Many-to-Many)
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_subject', 'subject_id', 'department_id');
    }
}
