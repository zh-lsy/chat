<?php
include 'vendor/autoload.php';

use Myclass\Config;



$config = Config::instance();

print_r($config['jwt']);
// $server = new Swoole\WebSocket\Server("0.0.0.0", 9501);

// $server->on('open', function (Swoole\WebSocket\Server $server, $request) {
//     echo "server: handshake success with fd{$request->fd}\n";
// });

// $server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
//     echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
//     $server->push($frame->fd, "this is server");
// });

// $server->on('close', function ($ser, $fd) {
//     echo "client {$fd} closed\n";
// });

// $server->start();

?>
