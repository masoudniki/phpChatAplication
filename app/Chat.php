<?php

namespace App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface {
	protected $clients;
	protected $users;

	public function __construct() {
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn){
		$this->clients->attach($conn);
		$msg="New Device [ID : $conn->resourceId] Connected";
		echo $msg;
		foreach ($this->clients as $client)
        {
            if($conn->resourceId!==$client->resourceId)
            {
                $client->send(json_encode(array("type"=>'server_msg',"msg"=>$msg)));
            }
        }

    		// $this->users[$conn->resourceId] = $conn;
	}

	public function onClose(ConnectionInterface $conn) {
		$this->clients->detach($conn);
		// unset($this->users[$conn->resourceId]);
	}

	public function onMessage(ConnectionInterface $from,  $data) {
		$from_id = $from->resourceId;
		$data = json_decode($data);
		$type = $data->type;
		switch ($type) {
			case 'chat':
				$user_id = $data->user_id;
				$chat_msg = $data->user_msg;
				// Output
				foreach($this->clients as $client)
				{
				    if($from_id!==$client->resourceId)
                    {

                        $client->send(json_encode(array("type"=>"input_msg","msg"=>"$user_id  => "."$chat_msg")));

                    }

				}
				break;
		}
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		$conn->close();
	}
}

