<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    public function classes()
    {
        return $this->hasMany(MyClass::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
