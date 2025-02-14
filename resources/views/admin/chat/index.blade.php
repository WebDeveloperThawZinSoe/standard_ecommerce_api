@extends('layouts.admin')

@section('body')
<div class="container">
    <h2>Messages</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Last Message</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $key=>$user)
                @php
                    $adminId = auth()->id();
                    $lastMessage = \App\Models\Message::where(function ($query) use ($adminId, $user) {
                        $query->where(function ($q) use ($adminId, $user) {
                            $q->where('sender_id', $adminId)
                              ->where('receiver_id', $user->id);
                        })
                        ->orWhere(function ($q) use ($adminId, $user) {
                            $q->where('sender_id', $user->id)
                              ->where('receiver_id', $adminId);
                        });
                    })->latest()->first();
                @endphp

                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        {{ $lastMessage->message ?? 'No messages' }}

                        @if ($lastMessage && $lastMessage->is_read == 0 && $lastMessage->receiver_id == $adminId)
                            <span class="badge bg-danger"><span style="color:white">New Message</span></span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.messages.detail', $user->id) }}" class="btn btn-info">View Chat</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
