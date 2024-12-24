<?php

namespace App\WebSocketHandlers;

use Ratchet\ConnectionInterface;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;
use App\Models\Notification;

class NotificationHandler extends WebSocketHandler
{
    public function onOpen(ConnectionInterface $connection)
    {
        // Code exécuté lorsqu'une nouvelle connexion WebSocket est ouverte
    }

    public function onClose(ConnectionInterface $connection)
    {
        // Code exécuté lorsqu'une connexion WebSocket est fermée
    }

    public function onMessage(ConnectionInterface $connection, $payload)
    {
        // Code exécuté lorsqu'un message est reçu depuis un client WebSocket
        // Traitez le message pour déclencher l'envoi de notifications
        $data = json_decode($payload, true);

        if (isset($data['user_id'])) {
            // Récupérez les informations de la notification depuis le payload
            $title = $data['title'];
            $content = $data['content'];

            // Enregistrez la notification en base de données
            Notification::create([
                'title' => $title,
                'content' => $content,
            ]);

            // Envoyez la notification à tous les clients connectés
            $this->broadcast(json_encode([
                'type' => 'notification',
                'data' => [
                    'title' => $title,
                    'content' => $content,
                ],
            ]));
        }
    }
}
