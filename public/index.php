<?php

// Include the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Import necessary classes
use Slim\Factory\AppFactory;
use Slim\Middleware\BodyParsingMiddleware;
use ChatApp\Controllers\{GroupController, MessageController, UserController};
use ChatApp\Database\Database;
use ChatApp\Middleware\AuthMiddleware;

// Initialize database
$database = new Database();
$db = $database->getConnection();

// Initialize app
$app = AppFactory::create();

// BodyParsingMiddleware to parse JSON request bodies
$app->add(new BodyParsingMiddleware());

// Set the base path if the application is running in a subdirectory
$app->setBasePath('/chat-app/public');

// Error middleware
$app->addErrorMiddleware(true, true, true);

// Initialize controllers
$userController = new UserController($db);
$groupController = new GroupController($db);
$messageController = new MessageController($db);

// Auth middleware
$authMiddleware = new AuthMiddleware($db);

// Define routes
// User routes
$app->post('/users', [$userController, 'createUser']);

// Protected routes
$app->group('', function ($group) use ($groupController, $messageController) {
    // Group routes
    $group->post('/groups', [$groupController, 'createGroup']);
    $group->get('/groups', [$groupController, 'listGroups']);
    $group->post('/groups/{group_id}/join', [$groupController, 'joinGroup']);
    
    // Message routes
    $group->post('/messages', [$messageController, 'sendMessage']);
    $group->get('/groups/{group_id}/messages', [$messageController, 'listMessages']);
})->add($authMiddleware);

// Run app
$app->run();