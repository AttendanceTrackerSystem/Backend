<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'student_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_number',
        'full_name',
        'email',
        'password',
        'dept_id',
    ];

    protected $hidden = [
        'password',
    ];

    // Each Student belongs to one Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
}
