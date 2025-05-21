<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userMessage = $_POST['message'];
    
    // Simple Response (Replace with AI logic)
    $responses = [
        "hello" => "Hello! How can I assist you?",
        "price" => "You can filter products by price on our marketplace.",
        "bye" => "Goodbye! Have a great day!"
    ];
    
    $reply = "I'm not sure, please ask again.";
    foreach ($responses as $key => $response) {
        if (strpos(strtolower($userMessage), $key) !== false) {
            $reply = $response;
            break;
        }
    }

    echo json_encode(["reply" => $reply]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>BhoomiAI</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        #chatbox { max-width: 100%; height: 400px; overflow-y: auto; border-bottom: 1px solid #ddd; padding: 10px; }
        #user-input { width: 90%; padding: 10px; }
        #send-button { padding: 10px; cursor: pointer; background: green; color: white; border: none; }
    </style>
</head>
<body>
    <div id="chatbox"></div>
    <input type="text" id="user-input" placeholder="Type a message..." onkeypress="if(event.key==='Enter') sendMessage()">
    <button id="send-button" onclick="sendMessage()">Send</button>

    <script>
        function sendMessage() {
            var userMessage = document.getElementById("user-input").value;
            if (userMessage.trim() === "") return;

            var chatbox = document.getElementById("chatbox");
            chatbox.innerHTML += "<div><b>You:</b> " + userMessage + "</div>";

            fetch("chatbot.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "message=" + encodeURIComponent(userMessage)
            })
            .then(response => response.json())
            .then(data => {
                chatbox.innerHTML += "<div><b>BhoomiAI:</b> " + data.reply + "</div>";
                document.getElementById("user-input").value = "";
                chatbox.scrollTop = chatbox.scrollHeight;
            });
        }
    </script>
</body>
</html>
