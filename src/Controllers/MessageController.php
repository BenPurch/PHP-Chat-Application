<?php

namespace ChatApp\Controllers;

use ChatApp\Models\Message;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessageController {
    private $messageModel; // Property to hold the Message model instance

    // Constructor to initialize the Message model with a database connection
    public function __construct($db) {
        $this->messageModel = new Message($db);
    }

    // Method to handle sending a message to a group
    public function sendMessage(Request $request, Response $response): Response {
        // Parse the request body to get the user ID, group ID, and message
        $data = $request->getParsedBody();
        $userId = $data['user_id'];
        $groupId = $data['group_id'];
        $message = $data['message'];

        // Create the message using the Message model
        $messageId = $this->messageModel->create($userId, $groupId, $message);

        // Return the message ID in the response with a 201 status code
        $payload = json_encode(['message_id' => $messageId]);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    // Method to handle listing all messages in a specific group
    public function listMessages(Request $request, Response $response, array $args): Response {
        // Get the group ID from the route parameters
        $groupId = $args['group_id'];

        // Fetch all messages for the group using the Message model
        $messages = $this->messageModel->findByGroup($groupId);

        // Return the list of messages in the response
        $payload = json_encode($messages);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}