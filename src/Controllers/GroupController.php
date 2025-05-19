<?php

namespace ChatApp\Controllers;

use ChatApp\Models\Group;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GroupController {
    private $groupModel; // Property to hold the Group model instance

    // Constructor to initialize the Group model with a database connection
    public function __construct($db) {
        $this->groupModel = new Group($db);
    }

    // Method to handle creating a new group
    public function createGroup(Request $request, Response $response): Response {
        // Parse the request body to get the group name
        $data = $request->getParsedBody();
        $name = $data['name'];

        // Create the group using the Group model
        $groupId = $this->groupModel->create($name);

        // Return the group ID in the response with a 201 status code
        $payload = json_encode(['group_id' => $groupId]);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    // Method to handle listing all groups
    public function listGroups(Request $request, Response $response): Response {
        // Fetch all groups using the Group model
        $groups = $this->groupModel->findAll();

        // Return the list of groups in the response
        $payload = json_encode($groups);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function joinGroup(Request $request, Response $response, array $args): Response {
        // Get the group ID from the route parameters
        $groupId = $args['group_id'];
        
        // Get the authenticated user from the request attributes
        $user = $request->getAttribute('user');
        $userId = $user['id'];
        
        // Add the user to the group
        $this->groupModel->addMember($userId, $groupId);
        
        // Return a success response
        $payload = json_encode(['success' => true]);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}