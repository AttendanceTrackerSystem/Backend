<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $primaryKey = 'id'; // or 't_id' if custom PK

    protected $fillable = [
        'teacher_email',
        'name',
        'phone_number',
        'password',
        'department_id',
    ];

    // Optional: Relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Optional: Relationship with Classes
    public function classes()
    {
        return $this->hasMany(Classes::class, 'teacher_id', 'id');
    }
}
