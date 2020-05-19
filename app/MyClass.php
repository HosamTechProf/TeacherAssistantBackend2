<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyClass extends Model
{
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'my_class_students', 'class_id', 'student_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function lectures()
    {
        return $this->hasManyThrough(
            Lecture::class,
            Subject::class,
            'class_id', // Foreign key on subjects table...
            'subject_id', // Foreign key on lectures table...
            'id', // Local key on classes table...
            'id' // Local key on subjects table...
        );
    }

    public function messages()
    {
        return $this->hasMany(Message::class, "class_id");
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, "class_id");
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, "class_id");
    }
}
