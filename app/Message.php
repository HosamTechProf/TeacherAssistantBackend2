<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message', 'class_id'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function myClass()
    {
    	return $this->belongsTo(MyClass::class);
    }
}
