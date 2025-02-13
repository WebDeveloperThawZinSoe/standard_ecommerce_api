@extends('layouts.admin')

@section('body')
<div class="container">
    <h2>Messages</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Last Message</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                @php
                    $chatUser = auth()->id() == $user->sender_id ? $user->receiver : $user->sender;
                @endphp
                <tr>
                    <td>{{ $chatUser->name ?? 'Unknown User' }}</td>
                    <td>
                        {{ \App\Models\Message::where('sender_id', $chatUser->id)
                            ->orWhere('receiver_id', $chatUser->id)
                            ->latest()
                            ->first()
                            ->message ?? 'No messages' }}
                        @php 
                        $is_read = App\Models\Message::where('sender_id', $chatUser->id)
                            ->orWhere('receiver_id', $chatUser->id)
                            ->latest()
                            ->first()
                            ->is_read;
                        @endphp
                        @if($is_read == 0)
                        <span class="badge bg-danger"><span style="color:white">New Message</span></span>
                        
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.messages.detail', $chatUser->id) }}" class="btn btn-info">View Chat</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
