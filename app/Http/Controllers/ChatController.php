<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Message;
use App\MyClass;
use App\User;
use App\Student;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = new Message;
        $message->message = $request->message;
        $message->class_id = $request->class_id;
        $message->user_id = Auth()->user()->id;
        $message->save();
        
		// broadcast(new MessageSent(auth()->user(), $message))->toOthers();

        return response()->json(['status' => true]);
    }

    public function deleteMessage($messageId)
    {
        $message = Message::find($messageId);
        $message->delete();
        return response()->json(['status'=>true]);
    }

    public function getMessages($classId)
    {
    	$myClass = MyClass::find($classId);
    	$messages = $myClass->messages;
        foreach ($messages as $message) {
            if ($message->user_id == null) {
                $message->sender = Student::select('name','image')->where('id', $message->student_id)->first();
            }elseif ($message->student_id == null) {
                $message->sender = User::select('name','image')->where('id', $message->user_id)->first();
            }
        }
        return response()->json(['messages' => $messages]);
	}
}