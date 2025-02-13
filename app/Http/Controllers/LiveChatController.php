<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LiveChatController extends Controller
{
    public function livechat()
    {
        $user_id = 1;
        $messages = Message::where(function ($query) use ($user_id) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $user_id);
        })
        ->orWhere(function ($query) use ($user_id) {
            $query->where('sender_id', $user_id)->where('receiver_id', Auth::id());
        })
        ->orderBy('created_at', 'asc')
        ->get();
        $user = User::findOrFail($user_id);
    

    // Debug log
    Log::info('Chat detail page loaded', ['user_id' => $user_id]);

    // return view('admin.chat.detail', compact('messages', 'user'));
        return view("customer.livechat",compact("messages","user"));
    }

    public function livechatAdmin(){
        $users = Message::with('sender', 'receiver')
        ->select('sender_id', 'receiver_id')
        ->groupBy('sender_id', 'receiver_id')
        ->get();

    return view('admin.chat.index', compact('users'));
       
    }

    public function livechatDetail($user_id)
    {
        $messages = Message::where(function ($query) use ($user_id) {
                $query->where('sender_id', Auth::id())->where('receiver_id', $user_id);
            })
            ->orWhere(function ($query) use ($user_id) {
                $query->where('sender_id', $user_id)->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $user = User::findOrFail($user_id);

        // Debug log
        Log::info('Chat detail page loaded', ['user_id' => $user_id]);

        return view('admin.chat.detail', compact('messages', 'user'));
    }

    // public function sendMessage(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'receiver_id' => 'required|integer|exists:users,id',
    //             'message' => 'required|string'
    //         ]);

    //         $message = Message::create([
    //             'sender_id' => Auth::id(),
    //             'receiver_id' => $request->receiver_id,
    //             'message' => $request->message,
    //             'is_read' => false
    //         ]);

    //         broadcast(new MessageSent($message))->toOthers();

    //         Log::info('Message sent successfully', ['message_id' => $message->id]);

    //         return response()->json(['status' => 'Message Sent!', 'message' => $message]);
    //     } catch (\Exception $e) {
    //         Log::error('Error sending message:', [
    //             'error' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine()
    //         ]);

    //         return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 500);
    //     }
    // }
    public function sendMessage(Request $request)
{
    \Log::info("Test");
    try {
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        \Log::info('Message sent successfully', ['message_id' => $message->id]);

        return response()->json([
            'status' => 'Message Sent!',
            'message' => $message
        ]);
    } catch (\Exception $e) {
        \Log::error('Message sending failed', ['error' => $e->getMessage()]);
        return response()->json([
            'status' => 'Error',
            'message' => 'Failed to send message'
        ], 500);
    }
}

}
