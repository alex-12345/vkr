<?php

use Workerman\Worker;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;

require_once __DIR__ . '/vendor/autoload.php';

$storage = [
    'ROLE_USER' => [],
    'ROLE_MODERATOR' => [],
    'ROLE_ADMIN' => [],
    'ROLE_SUPER_ADMIN' => []
];

$ws_connections = [
    'ROLE_USER' => [],
    'ROLE_MODERATOR' => [],
    'ROLE_ADMIN' => [],
    'ROLE_SUPER_ADMIN' => []
];

$ws_worker = new Worker("websocket://0.0.0.0:8001");
$publicKey = new Key('file://jwt/public.pem');
$signer = new Sha256();

$ws_worker->onConnect = function($connection) use (&$ws_connections, &$publicKey, &$signer)
{
    $connection->onWebSocketConnect = function($connection) use (&$ws_connections, &$publicKey, &$signer)
    {
        $encodeToken = $_GET['token'] ?? '';
        try {
            $token = (new Parser())->parse($encodeToken);
        }
        catch (Exception $e)
        {
            $connection->close(json_encode(['error' => ['code' => '400', 'Bad content!']]));
        }

        if(isset($token)) {
            $data = new ValidationData();
            if($token->validate($data)){
                if($token->verify($signer, $publicKey)){
                    $roles = implode($token->getClaim('roles'));
                    $user_id =  $token->getClaim('id');
                    //TODO REFACTOR THIS
                    $ws_connections[$roles][$user_id] = $connection;
                }else{
                    $connection->close(json_encode(['error' => ['code' => '403', 'Invalid VERIFY SIGNATURE!']]));
                };

            }else{
                $connection->close(json_encode(['error' => ['code' => '401', 'Token expired!']]));
            }
        }

    };
};

$ws_worker->onClose = function($connection) use(&$ws_connections)
{
    for($i = 0, $user = false; $user && $i < count($ws_connections); $i++){
        $user = array_search($connection, $ws_connections[$i]);
        if(!$user) unset($ws_connections[$i][$user]);
    }
};

$ws_worker->onWorkerStart = function() use (&$ws_connections) {

    $http_worker = new Worker('http://0.0.0.0:2345');

    $http_worker->onMessage = function ($http_connection, $request) use (&$ws_connections)  {

        $json_data = $request->post()['json_data'] ?? '';
        $token = $request->get()['token'] ?? '';

        if (sha1($json_data . 'e819a74eba9e4c4e2db44a100164019a') === $token) {
            $data = json_decode($json_data, true);

            $notificationsRoles = $data["notifications_roles"];
            $notificationsUserIds = $data['notifications_user_ids'];
            $output_data = $data['output_data'];

            //TODO find connection and emitt it and add to store

            $http_connection->send("Correct");
        } else {
            $http_connection->send("bad request");
        };
    };
};

Worker::runAll();
