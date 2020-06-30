<?php
$session = mt_rand(1,999);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Chat</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<script src="js/jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<style type="text/css">
	* {margin:0;padding:0;box-sizing:border-box;font-family:arial,sans-serif;resize:none;}
	html,body {width:100%;height:100%;}
	#wrapper {position:relative;margin:auto;max-width:1000px;height:100%;}
	#chat_output {position:absolute;top:0;left:0;padding:20px;width:100%;height:calc(100% - 100px);}
	#chat_input {position:absolute;bottom:0;left:0;padding:10px;width:100%;height:100px;border:1px solid #ccc;}
    #send{

        position: absolute;
        bottom: 0 ;
        right: 100px;

    }
    .container {
        border: 2px solid #dedede;
        background-color: #f1f1f1;
        border-radius: 5px;
        padding: 10px;
        margin: 10px 0;
    }

    .darker {
        border-color: #ccc;
        background-color: #ddd;
    }

    .container::after {
        content: "";
        clear: both;
        display: table;
    }

    .container img {
        float: left;
        max-width: 60px;
        width: 100%;
        margin-right: 20px;
        border-radius: 50%;
    }

    .container img.right {
        float: right;
        margin-left: 20px;
        margin-right:0;
    }

    .time-right {
        float: right;
        color: #aaa;
    }

    .time-left {
        float: left;
        color: #999;
    }
    #serverMsg{
        color: green;
        font-size: 25px;

    }





	</style>
</head>
<body>
	<div id="wrapper">
		<div id="chat_output"></div>
        <label for="chat_input">WriteYourMsg</label>
        <input type="text" id="chat_input" placeholder="type your msg .....">


        <button id="send" type="button" onclick="sendMsg()" class="btn btn-dark btn-lg">Send</button>
		<script type="text/javascript">
            let webSocket=new WebSocket("ws://192.168.2.125:8080");
            webSocket.addEventListener('open',function () {

                console.log("connections established");
                webSocket.send(
                JSON.stringify({
                    "type":"socket",
                    "user_id":<?php echo $session?>
                }))

            });

            webSocket.addEventListener('message',function (e) {

                console.log("new Message received");
                let Msg=JSON.parse(e.data);
                let chatBox=document.getElementById('chat_output');

                switch (Msg.type) {

                    case 'server_msg':
                       chatBox.innerHTML+=`<div class="container">
                          <p id="serverMsg">${Msg.msg}</p>
                        </div>`;
                        break;
                    case 'input_msg':
                        chatBox.innerHTML+=`<div class="container darker">
                          <p id="inputMsg">${Msg.msg}</p>
                        </div>`;
                        break;
                    case 'output_msg':
                        chatBox.innerHTML+=`
                          <div class="container">
                          <p id="outputMsg">${Msg.msg}</p>
                        </div>`;
                        break;



                }





            });


            function sendMsg() {

                let typedMsg=document.getElementById("chat_input");
                let chatBox=document.getElementById('chat_output');
                console.log(typedMsg.value);
                //console.log(typedMsg);
                //console.log(chatBox.value);
                webSocket.send(JSON.stringify({

                    "type":"chat",
                    "user_id":<?php echo $session?>,
                    "user_msg":typedMsg.value

                }));

                chatBox.innerHTML+=` <div class="container"> <p id="outputMsg">${typedMsg.value}</p> </div> `;
                typedMsg.value='  ';



            }






		</script>
	</div>
</body>
</html>