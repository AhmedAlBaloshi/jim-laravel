<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $chats = ChatMessage::with('sender', 'user')->where('uid', $request->user_id)->orderBy('created_at', 'asc')->get();
        return response()->json(['success' => 1, 'chats' => $chats]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'sender_id' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'message' => $validator->errors()]);
        }

        // Create the message
        $message = ChatMessage::create([
            'uid' => $request->user_id,
            'room_id' => 0,
            'from_id' => $request->sender_id,
            'message' => $request->message,
            'message_type' => 'users',
            'status' => '1',
            'timestamp' => date('Y-m-d H:i:s'),
        ]);

        return response()->json(['success' => 1, 'message' => $message]);
    }
}
