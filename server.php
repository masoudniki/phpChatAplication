<?php

require_once (__DIR__."/vendor/autoload.php");

    set_time_limit(0);
    use Ratchet\Http\HttpServer;
    use Ratchet\Server\IoServer;
    use Ratchet\WebSocket\WsServer;
    use App\Chat;






    $server = IoServer::factory(
        new HttpServer(new WsServer(new Chat())),
        8080
    );
    $server->run();
