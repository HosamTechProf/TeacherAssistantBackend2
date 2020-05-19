<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    public function myClass()
    {
        return $this->belongsTo(MyClass::class, "class_id");
    }
}
