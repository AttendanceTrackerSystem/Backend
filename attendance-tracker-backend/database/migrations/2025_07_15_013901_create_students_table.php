<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{

    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
    $table->string('student_number')->primary();
    $table->string('full_name');
    $table->string('email')->unique();
    $table->string('password');
    $table->unsignedBigInteger('dept_id'); // FK to departments
    $table->timestamps();

    $table->foreign('dept_id')->references('id')->on('departments')->onDelete('cascade');
});

    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
