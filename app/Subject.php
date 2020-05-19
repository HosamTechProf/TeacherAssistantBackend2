<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
