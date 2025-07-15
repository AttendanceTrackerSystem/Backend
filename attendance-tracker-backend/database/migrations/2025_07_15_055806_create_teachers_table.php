<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->string('teacher_id')->primary(); // custom ID like 'Tea001'
            $table->string('teacher_name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('password');

            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('subject_id');

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
