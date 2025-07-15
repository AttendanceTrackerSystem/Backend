<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentSubjectTable extends Migration
{
    public function up()
    {
        Schema::create('department_subject', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('department_id');
    $table->unsignedBigInteger('subject_id');
    $table->timestamps();

    $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
    $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
    $table->unique(['department_id', 'subject_id']);
});

    }
    public function down()
    {
        Schema::dropIfExists('department_subject');
    }
}
