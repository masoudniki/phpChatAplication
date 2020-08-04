<?php
require_once (__DIR__."/../vendor/autoload.php");

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

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




Capsule::schema()->create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->string('username')->unique();
    $table->timestamps();
    $table->string("password");
    $table->integer("ClientId")->nullable();
});
echo "data base migrated Successfully";


