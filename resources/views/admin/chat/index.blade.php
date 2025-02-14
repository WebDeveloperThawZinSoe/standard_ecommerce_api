@extends('layouts.admin')

@section('body')
<!-- Include Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


<div class="container">
    <h2>Messages</h2>
    <button class="btn btn-primary btn-tone m-r-5" data-toggle="modal" data-target="#createModal">
        <i class="anticon anticon-plus"></i> Start New Chat
    </button>
    <br><br>


    
<!-- Create Chat Modal -->
    <div class="modal fade" id="createModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Start New Chat</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="anticon anticon-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="startChatForm" method="POST" action="{{ route('admin.messages.detail2') }}">
                        @csrf
                        <div class="form-group">
                            <label for="userSelect">Select Users:</label>
                            <select id="userSelect" name="users" class="form-control">
                                @foreach($all_users as $all_user)
                                    <option value="{{ $all_user->id }}">
                                        {{ $all_user->name }} ({{ $all_user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Start Chat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('#userSelect').select2({
                placeholder: "Select users",
                allowClear: true
            });
        });
    </script>



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