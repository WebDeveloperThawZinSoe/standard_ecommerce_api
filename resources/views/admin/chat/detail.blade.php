@extends('layouts.admin')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg rounded-lg">
        <div class="card-header bg-chatColor text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="color:white !important;">Live Chat with {{$user->name}} </h5>

            <form action="{{route('admin.messages.clear')}}" method="post">
                @csrf
                <input type="hidden" name="sender" value="{{$user->id}}">
                <button type="submit" onclick="return confirm('Are You Sure To Clear This Chat ?')" class="mb-3 mt-3 btn btn-danger" style="color:white !important;">Clear Chat</button>
            </form>
        </div>
        <div id="chat-box" class="card-body p-3" style="height: 400px; overflow-y: auto; background-color: #f8f9fa; border-radius: 10px;">
            @foreach($messages as $message)
                <div class="d-flex {{ $message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                    <div class="p-2 rounded-lg text-white {{ $message->sender_id == auth()->id() ? 'bg-primary' : 'bg-chatColor' }}" style="max-width: 75%;">
                        {{ $message->message }}
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card-footer p-2">
            <div class="input-group">
                <input type="text" id="chat-message" class="form-control" placeholder="Type your message...">
                <input type="hidden" id="receiver-id" value="{{$user->id}}">
                <div class="input-group-append">
                    <button class="btn btn-primary" id="send-message"><i class="fas fa-paper-plane"></i> Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    Pusher.logToConsole = true;
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }
    });
    var userId = {{ auth()->id() }};
    var channel = pusher.subscribe('private-chat.' + userId);
    channel.bind('App\\Events\\MessageSent', function(data) {
        let messageBox = document.getElementById('chat-box');
        let newMessage = document.createElement('div');
        newMessage.innerHTML = `<div class='d-flex ${data.sender_id == userId ? 'justify-content-end' : 'justify-content-start'} mb-2'>
            <div class='p-2 rounded-lg text-white ${data.sender_id == userId ? 'bg-primary' : 'bg-chatColor'}' style='max-width: 75%;'>
                ${data.message}
            </div>
        </div>`;
        messageBox.appendChild(newMessage);
        messageBox.scrollTop = messageBox.scrollHeight;
    });
    document.getElementById('send-message').addEventListener('click', function() {
        var messageInput = document.getElementById('chat-message');
        var message = messageInput.value.trim();
        var receiverId = document.getElementById('receiver-id').value;
        if (message !== "") {
            fetch('/admin/send-message', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ receiver_id: receiverId, message: message })
            })
            .then(response => response.json())
            .then(data => {
                let messageBox = document.getElementById('chat-box');
                let newMessage = document.createElement('div');
                newMessage.innerHTML = `<div class='d-flex justify-content-end mb-2'>
                    <div class='p-2 rounded-lg text-white bg-primary' style='max-width: 75%;'>
                        ${message}
                    </div>
                </div>`;
                messageBox.appendChild(newMessage);
                messageBox.scrollTop = messageBox.scrollHeight;
                messageInput.value = '';
            })
            .catch(error => console.error("Error sending message:", error));
        }
    });
</script>
@endsection
