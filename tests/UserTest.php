<?php

use PHPUnit\Framework\TestCase;
use ChatApp\Controllers\UserController;
use ChatApp\Models\User;

class UserControllerTest extends TestCase
{
    private $db;
    private $userController;
    private $mockRequest;
    private $mockResponse;

    protected function setUp(): void
    {
        // Create a mock database connection
        $this->db = $this->createMock(PDO::class);
        
        // Create a mock user model
        $userModel = $this->createMock(User::class);
        $userModel->method('create')->willReturn(1);
        
        // Create a UserController instance with the mock db
        $this->userController = new UserController($this->db);
        
        // Set a property to inject the mock user model
        $reflection = new ReflectionClass($this->userController);
        $property = $reflection->getProperty('userModel');
        $property->setAccessible(true);
        $property->setValue($this->userController, $userModel);
        
        // Create mock request and response objects
        $this->mockRequest = $this->createMock('Psr\Http\Message\ServerRequestInterface');
        $this->mockResponse = $this->createMock('Psr\Http\Message\ResponseInterface');
        
        // Configure the mock response
        $this->mockResponse->method('withHeader')->willReturn($this->mockResponse);
        $this->mockResponse->method('withStatus')->willReturn($this->mockResponse);
        
        // Create a mock body
        $mockBody = $this->createMock('Psr\Http\Message\StreamInterface');
        $this->mockResponse->method('getBody')->willReturn($mockBody);
    }

    public function testCreateUser()
    {
        // Mock the request to return parsed body
        $this->mockRequest->method('getParsedBody')
            ->willReturn(['username' => 'testuser']);
        
        // Call the method
        $response = $this->userController->createUser($this->mockRequest, $this->mockResponse);
        
        // Assert response is returned
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }
}