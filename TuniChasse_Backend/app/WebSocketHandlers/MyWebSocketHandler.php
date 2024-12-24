<?php

namespace App\WebSocketHandlers;

use Ratchet\ConnectionInterface;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;

class MyWebSocketHandler extends WebSocketHandler
{
    public function onOpen(ConnectionInterface $connection)
    {
        // Code exécuté lorsqu'une nouvelle connexion WebSocket est ouverte
        echo "Nouvelle connexion WebSocket ouverte\n";
    }

    public function onClose(ConnectionInterface $connection)
    {
        // Code exécuté lorsqu'une connexion WebSocket est fermée
        echo "Connexion WebSocket fermée\n";
    }

    public function onMessage(ConnectionInterface $connection, $payload)
    {
        // Code exécuté lorsqu'un message est reçu depuis un client WebSocket
        // Vous pouvez traiter le message et éventuellement envoyer une réponse
        echo "Message reçu depuis le client : " . $payload . "\n";

        // Vous pouvez également envoyer une réponse au client
        $connection->send('Message reçu : ' . $payload);
    }
}
