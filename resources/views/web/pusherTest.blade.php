<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusher Test</title>
</head>
<body>
    <h1 id="message">Waiting for data...</h1>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('ca0b7ef0d3a4edcc8853', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('user-registrations');
        channel.bind('new-user.registered', function(data) {
            console.log("Received Data:", data); // Check if data is received in the browser

            if (data && data.message) {
                document.getElementById('message').textContent = data.message;
            } else {
                console.error("No 'message' key found in data:", data);
            }
        });
    </script>
</body>
</html>
