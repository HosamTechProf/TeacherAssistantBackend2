<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

class Student extends Authenticatable
{
    use Notifiable, HasMultiAuthApiTokens;
    
    public function classes()
    {
        return $this->belongsToMany(MyClass::class, 'my_class_students', 'student_id', 'class_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function lectures()
    {
        return $this->belongsToMany(Lecture::class, 'student_lecture')->withPivot(["type","id"]);
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'student_grade')->withPivot(["grade"]);
    }
}
