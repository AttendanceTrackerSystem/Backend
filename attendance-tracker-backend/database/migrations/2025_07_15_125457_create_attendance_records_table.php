<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_records', function (Blueprint $table) {
        $table->id();
        $table->string('student_id'); 
        $table->unsignedBigInteger('class_id');
        $table->boolean('is_present')->default(true);
        $table->tinyInteger('rating')->nullable();
        $table->text('comment')->nullable();
        $table->date('date');
        $table->timestamps();

        $table->foreign('student_id')->references('student_number')->on('students')->onDelete('cascade');
        $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
    });
    }
    public function down()
    {
        Schema::dropIfExists('attendance_records');
    }
}
