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
    // Log::info('Chat detail page loaded', ['user_id' => $user_id]);

    // return view('admin.chat.detail', compact('messages', 'user'));
        return view("customer.livechat",compact("messages","user"));
    }

    public function livechatAdmin()
    {
        $all_users = User::where("role","2")->get();
        $adminId = auth()->id(); // Get logged-in admin ID

        // Fetch unique users who have chatted with the admin
        $users = User::whereHas('sentMessages', function ($query) use ($adminId) {
                    $query->where('receiver_id', $adminId);
                })
                ->orWhereHas('receivedMessages', function ($query) use ($adminId) {
                    $query->where('sender_id', $adminId);
                })->orderBy("created_at","desc")
                ->distinct()
                ->get();

        return view('admin.chat.index', compact('users','all_users'));
    }

    public function livechatDetail2(Request $request){
        $user_id = $request->users;
        Message::where('sender_id', $user_id)->update([
            "is_read" => 1
        ]);

        // dd($user_id);

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
        //Log::info('Chat detail page loaded', ['user_id' => $user_id]);

        return view('admin.chat.detail', compact('messages', 'user'));
    }
    
    public function livechatDetail($user_id)
    {
        Message::where('sender_id', $user_id)->update([
            "is_read" => 1
        ]);

        // dd($user_id);

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
        //Log::info('Chat detail page loaded', ['user_id' => $user_id]);

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
        // \Log::info("Test");
        try {
            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ]);

            broadcast(new MessageSent($message))->toOthers();

            // \Log::info('Message sent successfully', ['message_id' => $message->id]);

            return response()->json([
                'status' => 'Message Sent!',
                'message' => $message
            ]);
        } catch (\Exception $e) {
            // \Log::error('Message sending failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'Error',
                'message' => 'Failed to send message'
            ], 500);
        }
    }

    public function clearChat(Request $request){
       $admin_id = Auth::id();
       $customer_id = $request->sender;
       Message::where("sender_id",$customer_id)->where("receiver_id",$admin_id)->delete();
       Message::where("sender_id",$admin_id)->where("receiver_id",$customer_id)->delete();
       return redirect()->back()->with('success', 'Clear Chat Success');
    }

}
