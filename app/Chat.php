<?php

namespace App;
use Firebase\JWT\JWT;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface {
	protected $clients;
	protected $users;
	protected $publicKey="-----BEGIN PUBLIC KEY-----
MIGeMA0GCSqGSIb3DQEBAQUAA4GMADCBiAKBgHOpKLzKLMTJxzfgtRL6Lw16/cj6
vCiZz/UdNBJIDCbf1MgQRmDvh6VQlO8X4CUiWQUjZ8qOJ7muyZlRpihLOJ8RsIXr
CajFAF1732wlgti6iNKVCqYIpx8mEnroRj9cuw7Efw5UcPSU/3h+QUhT0bztiYix
lB4ECbqbjU3dphNNAgMBAAE=
-----END PUBLIC KEY-----";

	public function __construct($DB) {
		$this->clients = new \SplObjectStorage;
		$this->DB=$DB;
	}

	public function onOpen(ConnectionInterface $conn){

		$this->clients->attach($conn);

	}

	public function onClose(ConnectionInterface $conn) {
		$this->clients->detach($conn);
		// unset($this->users[$conn->resourceId]);
	}

	public function onMessage(ConnectionInterface $from,  $data) {

		$from_id = $from->resourceId;
		$data = (object)json_decode($data);
        $token=$data->jwt_token;
		$decoded=false;
		try{
            $decoded = JWT::decode($token,$this->publicKey,['RS256']);

        }
        catch (\Exception  $e)
        {
             $e->getMessage();
        }


        if(!empty($data->jwt_token) and $decoded)
        {
            $type = $data->type;
            switch ($type) {
                case 'chat':
                    $username = $decoded->username;
                    $chat_msg = $data->user_msg;
                    // Output
                    foreach($this->clients as $client)
                    {
                        if($from_id!==$client->resourceId)
                        {
                            echo $username;

                            $client->send(json_encode(array("type"=>"input_msg","msg"=>" $username :"."$chat_msg")));

                        }

                    }
                    echo "send";
                    break;
            }


        }

        else
        {
            $from->send(json_encode([
                "type" => "server_msg",
                "status" => "you dont have access"]
            ));
            $this->clients->detach($from);
            $from->close();
            echo $from_id." disconnected not authorized \n ";

        }


	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		$conn->close();
	}
}

