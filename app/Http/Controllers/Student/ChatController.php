<?php

namespace App\Http\Controllers\Student;

use App\Events\MessageSent;
use App\Message;
use App\MyClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = new Message;
        $message->message = $request->message;
        $message->class_id = $request->class_id;
        $message->student_id = Auth()->user()->id;
        $message->save();

		// broadcast(new MessageSent(auth()->user(), $message))->toOthers();

        return response()->json(['status' => true]);
    }

    public function getMessages($classId)
    {
    	$myClass = MyClass::find($classId);
    	$messages = $myClass->messages;
        return response()->json(['messages' => $messages]);
	}
}