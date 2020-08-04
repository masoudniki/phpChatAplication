<?php
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'chat',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();







require_once (__DIR__."/vendor/autoload.php");

    set_time_limit(0);
    use Ratchet\Http\HttpServer;
    use Ratchet\Server\IoServer;
    use Ratchet\WebSocket\WsServer;
    use App\Chat;


    $server = IoServer::factory(
        new HttpServer(new WsServer(new Chat($capsule))),
        8080
    );
    $server->run();
