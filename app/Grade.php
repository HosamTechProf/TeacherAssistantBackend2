<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_grade')->withPivot(["grade"]);
    }

    public function myClass()
    {
        return $this->belongsTo(MyClass::class, "class_id");
    }
}
