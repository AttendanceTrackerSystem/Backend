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
}
