<?php
$session = mt_rand(1, 999);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Chat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <script src="js/jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style type="text/css">

    </style>
</head>

<body>
    <div id="wrapper">
        <div id="chat_output">
        </div>
        <div class="fixed-bottom">
            <input type="text" id="chat_input" placeholder="Write a message..." autofocus>
            <div class="center send-btn" onclick="sendMsg()">
                <i class="material-icons" style="font-size:40px;">send</i>
            </div>
        </div>
        <script type="text/javascript">
            let webSocket = new WebSocket("ws://localhost:8080");
            webSocket.addEventListener('open', function() {

                console.log("connections established");
                webSocket.send(
                    JSON.stringify({
                        "type": "socket",
                        "user_id": <?php echo $session ?>
                    }))

            });

            webSocket.addEventListener('message', function(e) {

                console.log("new Message received");
                let Msg = JSON.parse(e.data);
                let chatBox = document.getElementById('chat_output');

                switch (Msg.type) {
                    case 'server_msg':
                        chatBox.innerHTML += `<div class="speech-bubble">
                          <span id="serverMsg">${Msg.msg}</span>
                        </div>`;
                        break;
                    case 'input_msg':
                        chatBox.innerHTML += `
                          <div class="darker speech-bubble">
                          <span id="outputMsg">${Msg.msg}</span>
                        </div>`;
                        break;
                });

            function sendMsg() {
                let typedMsg = document.getElementById("chat_input");
                let chatBox = document.getElementById('chat_output');

                if (typedMsg.value) {
                    webSocket.send(JSON.stringify({
                        "type": "chat",
                        "user_id": <?php echo $session ?>,
                        "user_msg": typedMsg.value

                    }));

                    chatBox.innerHTML += ` <div class=" speech-bubble"> <span id="outputMsg">${typedMsg.value}</span> </div> `;
                    typedMsg.value = '';
                    var objDiv = document.querySelector('#chat_output');
                    objDiv.scrollTop = objDiv.scrollHeight;
                }
                $(typedMsg).focus()
            }
            document.body.addEventListener('keypress', function(event) {
                if (event.keyCode === 13)
                    sendMsg()
            })
        </script>
    </div>
</body>

</html>
