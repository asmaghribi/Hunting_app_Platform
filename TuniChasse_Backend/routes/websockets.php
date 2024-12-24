<?php
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use App\WebSocketHandlers\NotificationHandler;

WebSocketsRouter::webSocket('/my-websocket-endpoint', \App\WebSocketHandlers\MyWebSocketHandler::class);
Broadcast::channel('notifications', function ($user) {
    return true; // Autoriser tous les utilisateurs à écouter les notifications
});

Broadcast::channel('private-notifications.{userId}', function ($user, $userId) {
    return $user->id === $userId; // Autoriser uniquement l'utilisateur concerné à écouter ses propres notifications
});

Broadcast::channel('presence-notifications', function ($user) {
    return ['id' => $user->id, 'name' => $user->name]; // Informations de présence à partager avec les autres utilisateurs
});

WebSockets::channel('notifications', NotificationHandler::class);

