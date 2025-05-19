<?php

namespace ChatApp\Middleware;

use ChatApp\Models\User; // 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware {
    private $db; // Property to hold the database connection

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Middleware logic to authenticate requests
    public function __invoke(Request $request, RequestHandler $handler): Response {
        // Get the authorization token from the request headers
        $token = $request->getHeaderLine('Authorization');

        // Find the user associated with the token
        $userModel = new User($this->db);
        $user = $userModel->findByToken($token);

        // If no user is found, return a 401 Unauthorized response
        if (!$user) {
            $response = new \Slim\Psr7\Response();
            $payload = json_encode(['error' => 'Unauthorized']);
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        // Attach the user to the request for use in subsequent handlers
        $request = $request->withAttribute('user', $user);

        // Pass the request to the next handler
        return $handler->handle($request);
    }
}