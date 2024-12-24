<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\App;

class Chat implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $messageData = json_decode($msg, true); // Supposons que vous envoyiez le message sous forme de JSON
        $recipient = $messageData['recipient']; // Récupérez le destinataire du message depuis les données

        // Envoyez le message uniquement au destinataire s'il est connecté
        foreach ($this->clients as $client) {
            if ($client === $from) {
                continue;
            }

            // Si le destinataire est connecté, envoyez-lui le message
            if ($this->clients[$recipient] === $client) {
                $client->send($messageData['text']);
                break; // Sortez de la boucle une fois que le message est envoyé au destinataire
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

echo "Starting WebSocket server...\n";
$app = new App('0.0.0.0', 8000);
echo "Server created. Setting up routes...\n";
$app->route('/chat', new Chat, ['*']);
echo "Routes set up. Running server...\n";
$app->run();
echo "Server is running.\n";
