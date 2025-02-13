@extends('layouts.customer')

@section('body')
<div class="container">
    <h2>Live Chat (Customer)</h2>
    <div id="chat-box" class="mb-4" style="height: 400px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;">
        @foreach($messages as $message)
            <div style="margin-bottom: 10px; 
                {{ $message->sender_id == auth()->id() ? 'text-align: right;' : 'text-align: left;' }}">
                <strong>{{ $message->sender_id == auth()->id() ? 'You' : $user->name }}:</strong> 
                {{ $message->message }}
            </div>
        @endforeach
    </div>
    
    <div class="input-group">
        <input type="text" id="chat-message" class="form-control" placeholder="Type your message...">
        <input type="hidden" id="receiver-id" value="1"> <!-- Assuming Admin has ID 1 -->
        <div class="input-group-append">
            <button class="btn btn-primary" id="send-message">Send</button>
        </div>
    </div>
</div>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true,
        authEndpoint: '/broadcasting/auth', // Ensure private channels work
        auth: {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    });

    // Get the logged-in user ID
    var userId = {{ auth()->id() }};
    var channel = pusher.subscribe('private-chat.' + userId);

    // Listen for real-time messages
    channel.bind('App\\Events\\MessageSent', function(data) {
        console.log("New message received:", data);

        let messageBox = document.getElementById('chat-box');
        let newMessage = document.createElement('div');

        let senderName = data.sender_id == userId ? 'You' : data.sender_name;

        newMessage.innerHTML = `<strong>${senderName}:</strong> ${data.message}`;
        newMessage.style.textAlign = data.sender_id == userId ? 'right' : 'left';
        messageBox.appendChild(newMessage);
        messageBox.scrollTop = messageBox.scrollHeight;
    });

    // Sending message
    document.getElementById('send-message').addEventListener('click', function() {
        var messageInput = document.getElementById('chat-message');
        var message = messageInput.value;
        var receiverId = document.getElementById('receiver-id').value;

        if (message.trim() !== "") {
            console.log("Sending message:", message);

            fetch('/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Message sent response:", data);
                
                let messageBox = document.getElementById('chat-box');
                let newMessage = document.createElement('div');

                newMessage.innerHTML = `<strong>You:</strong> ${message}`;
                newMessage.style.textAlign = 'right';
                messageBox.appendChild(newMessage);
                messageBox.scrollTop = messageBox.scrollHeight;

                messageInput.value = ''; // Clear input after sending
            })
            .catch(error => console.error("Error sending message:", error));
        }
    });
</script>

@endsection
