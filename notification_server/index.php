<?php

use Workerman\Worker;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$httpServerURL = $_ENV['HTTP_SERVER'];
$wsServerURL = $_ENV['WS_SERVER'];

$publicKeyPath = $_ENV['PUBLIC_KEY'];
$appSecret = $_ENV['APP_SECRET'];

//$msg_storage[user_id][msg_stack]
$msg_storage = [];

//$ws_connections[ROLE_][user_id][connection array]
$ws_connections = [
    'ROLE_USER' => [],
    'ROLE_MODERATOR' => [],
    'ROLE_ADMIN' => [],
    'ROLE_SUPER_ADMIN' => []
];

$ws_worker = new Worker($wsServerURL);
$publicKey = new Key('file://'.$publicKeyPath);
$signer = new Sha256();

$ws_worker->onConnect = function($connection) use (&$ws_connections, &$msg_storage, &$publicKey, &$signer)
{
    $connection->onWebSocketConnect = function($connection) use (&$ws_connections, &$msg_storage, &$publicKey, &$signer)
    {
        $encodeToken = $_GET['token'] ?? '';
        try {
            $token = (new Parser())->parse($encodeToken);
        }
        catch (Exception $e)
        {
            $connection->close(json_encode(['error' => ['code' => 400, 'Bad content!']]));
        }

        if(isset($token)) {
            $data = new ValidationData();
            if($token->validate($data)){
                if($token->verify($signer, $publicKey)){
                    $roles = implode($token->getClaim('roles'));
                    $user_id =  $token->getClaim('id');
                    if(isset($msg_storage[$user_id]) and is_array($msg_storage[$user_id]) and count($msg_storage[$user_id]) > 0)
                    {
                        $connection->send(json_encode($msg_storage[$user_id]));
                    }
                    $ws_connections[$roles][$user_id][] = $connection;
                    //echo 'user '.$user_id.' connected';
                }else{
                    $connection->close(json_encode(['error' => ['code' => 403, 'Invalid VERIFY SIGNATURE!']]));
                };

            }else{
                $connection->close(json_encode(['error' => ['code' => 401, 'Token expired!']]));
            }
        }

    };
};

$ws_worker->onClose = function($connection) use(&$ws_connections)
{
    foreach($ws_connections as $role_name => $role_connections){// O(4)
        foreach ($role_connections as $user_id => $user){ //O(n), n - amount connected users
            if(!is_array($user)) continue;
            foreach($user as $connection_id => $user_connection)  // O(m) m - connections per user (avg 1 - 5)
            {
                if($connection === $user_connection)
                {
                    unset($ws_connections[$role_name][$user_id][$connection_id]);
                    if(count($ws_connections[$role_name][$user_id]) === 0) unset($ws_connections[$role_name][$user_id]);
                    //echo 'user '.$user_id.' disconnected';
                    break 3;
                }
            }
        }
    }
};

$ws_worker->onWorkerStart = function() use (&$ws_connections, &$msg_storage, &$httpServerURL, &$appSecret) {

    $http_worker = new Worker($httpServerURL);

    $http_worker->onMessage = function ($http_connection, $request) use (&$ws_connections, &$msg_storage, &$appSecret)  {
        $json_data = $request->post()['json_data'] ?? '';
        $token = $request->get()['token'] ?? '';

        if (sha1($json_data . $appSecret) === $token) {
            $data = json_decode($json_data, true);

            $notificationsRoles = $data["notifications_roles"];
            $notificationsUserIds = $data['notifications_user_ids'];
            $output_data = $data['output_data'];

            $sendingData = json_encode($output_data);

            foreach($notificationsUserIds as $user_id)
            {
                if($output_data['event_name'] == 'MessageCreated') {
                    //TODO add removing messages
                    $msg_storage[$user_id] [] = $output_data;
                }
                if(isset($ws_connections['ROLE_USER'][$user_id]) && count($ws_connections['ROLE_USER'][$user_id]) > 0)
                {
                    foreach ($ws_connections['ROLE_USER'][$user_id] as $connection)
                    {
                        $connection->send($sendingData);
                    }
                }
            }

            foreach ($notificationsRoles as $notifyRole) // max 3 iteration
            {
                foreach ($ws_connections[$notifyRole] as $notifyUser) //amount active users
                {
                    if(count($notifyUser) > 0) {
                        foreach ($notifyUser as $connection){ // max - amount connection per user
                            $connection->send($sendingData);
                        }
                    }
                }
            }

            $http_connection->send(json_encode(['code' => 200]));
        } else {
            $http_connection->send(json_encode(['error' => ['code' => 400, 'Bad request!']]));
        };
    };
    $http_worker->listen();
};

Worker::runAll();