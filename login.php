<?php


    require_once (__DIR__."/vendor/autoload.php");
    use \Firebase\JWT\JWT;
    $privateKey = "-----BEGIN RSA PRIVATE KEY-----
MIICWgIBAAKBgHOpKLzKLMTJxzfgtRL6Lw16/cj6vCiZz/UdNBJIDCbf1MgQRmDv
h6VQlO8X4CUiWQUjZ8qOJ7muyZlRpihLOJ8RsIXrCajFAF1732wlgti6iNKVCqYI
px8mEnroRj9cuw7Efw5UcPSU/3h+QUhT0bztiYixlB4ECbqbjU3dphNNAgMBAAEC
gYANs7iTxQ/QsGbdg81v1hvE6REvwiSQWsh8LV2B3O8zm8jFesgbq8TSHN3IBXgU
biFFrpAZOwYRxVc0xqt8koy+gkMmIb5RsGq9NQnSPchqIU5oiqDdYRw5b8PF6Yz6
dx30OPPA0KaqgoCwxyLYW2BVjLYfrPkt6DsH9PF0p83XAQJBAMjoymJs3XtUZd6D
4PmeqLA0DjPX3QRStjVGY/lIqihlhQAyDQOe6hBcryU1cD48FwQ4gpTylH6L987o
+4G0vz0CQQCTYC9vq5WmYHjf/pWal4/eQwhmsQEjI6iDJ/ZQGUeEgojhL4kEms54
IfP0S0zdxaA0owwTol2OKkSUrHI8NOVRAkAsn2e0DLH0nn4xueDSEGcvG6C76wnv
198YXhX+XCFO751mubciQr2B/NP507CmYfpKubJnGqnYoYXcsuqJmHeRAkBFndQK
njUM6NlwhiRJaHrvdR7M06RD4x5BBmmWILrl33ulU/0XjcEmgnNo3QEsaaRp+PNF
exdShqjqyiMUSbBhAkB/ZZypZaOixwd3kO7IYGNwb0SSUj/S1+SSXOPPp2fDeZVk
9eGVp+f1yQm3ng6X4oxFPjHmIKR6gB/zlr24JE56
-----END RSA PRIVATE KEY-----";

use Illuminate\Database\Capsule\Manager as Capsule;
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


    class login{

        private $DB;
        private $userName;
        private $password;
        private $privateKey;
        public function __construct(Capsule $DB,$privateKey)
        {
            $this->DB=$DB;
            $this->userName=$_POST['username'] ?? false;
            $this->password=$_POST['password'] ?? false;
            $this->privateKey=$privateKey;

        }
        public function checkCredential(){

            if(isset($_POST['submit']) && $this->userName){

                $result=$this->DB::select('select * from user where username = ? and password=?',[$this->userName,$this->password]);
                if(count($result)>=1)
                {
                    $payload = array(
                           "username" => $this->userName

                       );
                       $jwt = JWT::encode($payload, $this->privateKey,'RS256');
                       header('Content-Type: application/json');
                       $response=json_encode([
                           "jwt_token"=>$jwt,
                           "redirect_page"=>"index.php"
                       ]);
                      echo $response;
                }
                else{
                    echo "user name or password incorrect";
                }
            }
            else{
                echo "bad";
            }

        }


    }


    $Login=new login($capsule,$privateKey);
    $Login->checkCredential();






