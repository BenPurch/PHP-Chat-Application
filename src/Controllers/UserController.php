<?php

namespace ChatApp\Controllers;

use ChatApp\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    // Method to handle creating a new user
    public function createUser(Request $request, Response $response): Response {
        // Get the raw request body
        $rawBody = (string) $request->getBody();
        echo "Raw Request Body: " . $rawBody . "\n";

        // Parse the request body
        $data = $request->getParsedBody();
        echo "Parsed Request Body: ";
        print_r($data);
        echo "\n";

        // Validate input
        if (!isset($data['username']) || empty(trim($data['username']))) {
            $payload = json_encode(['error' => 'Username is required']);
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $username = trim($data['username']);

        // Check if username is already taken
        try {
            if ($this->userModel->findByUsername($username)) {
                $payload = json_encode(['error' => 'Username is already taken']);
                $response->getBody()->write($payload);
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(409);
            }

            // Generate a random token for the user
            $token = bin2hex(random_bytes(16));

            // Create the user using the User model
            $userId = $this->userModel->create($username, $token);

            // Return the user ID and token in the response with a 201 status code
            $payload = json_encode(['user_id' => $userId, 'token' => $token]);
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } catch (\Exception $e) {
            // Log the error to the terminal
            echo "Error: " . $e->getMessage() . "\n";
            
            // Return a generic error message
            $payload = json_encode(['error' => 'An error occurred while creating the user']);
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}