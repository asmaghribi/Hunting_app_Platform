<?php

require __DIR__ . '/../vendor/autoload.php';

use Ratchet\Client\WebSocket;

$loop = React\EventLoop\Factory::create();
$connector = new Ratchet\Client\Connector($loop);

$connector('ws://localhost:6001')->then(function (WebSocket $conn) {
    $conn->on('message', function ($msg) use ($conn) {
        echo "Received: {$msg}\n";
        // Vous pouvez ajouter ici du code pour vérifier les réponses du serveur
        $conn->close();
    });

    $conn->send(json_encode([
        'user_id' => 1,
        'title' => 'Test Notification',
        'content' => 'This is a test notification',
    ]));
}, function ($e) {
    echo "Could not connect: {$e->getMessage()}\n";
});

$loop->run();
