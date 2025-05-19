<?php

use PHPUnit\Framework\TestCase;
use ChatApp\Controllers\MessageController;
use ChatApp\Models\Message;

class MessageControllerTest extends TestCase
{
    private $db;
    private $messageController;
    private $mockRequest;
    private $mockResponse;

    protected function setUp(): void
    {
        // Create a mock database connection
        $this->db = $this->createMock(PDO::class);
        
        // Create a mock message model
        $messageModel = $this->createMock(Message::class);
        $messageModel->method('create')->willReturn(1);
        $messageModel->method('findByGroup')->willReturn([
            ['id' => 1, 'user_id' => 1, 'group_id' => 1, 'message' => 'Test message']
        ]);
        
        // Create a MessageController instance with the mock db
        $this->messageController = new MessageController($this->db);
        
        // Set a property to inject the mock message model
        $reflection = new ReflectionClass($this->messageController);
        $property = $reflection->getProperty('messageModel');
        $property->setAccessible(true);
        $property->setValue($this->messageController, $messageModel);
        
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

    public function testSendMessage()
    {
        // Mock the request to return parsed body
        $this->mockRequest->method('getParsedBody')
            ->willReturn(['user_id' => 1, 'group_id' => 1, 'message' => 'Test message']);
        
        // Call the method
        $response = $this->messageController->sendMessage($this->mockRequest, $this->mockResponse);
        
        // Assert response is returned
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }

    public function testListMessages()
    {
        // Call the method
        $response = $this->messageController->listMessages($this->mockRequest, $this->mockResponse, ['group_id' => 1]);
        
        // Assert response is returned
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }
}