<?php

use PHPUnit\Framework\TestCase;
use ChatApp\Controllers\GroupController;
use ChatApp\Models\Group;

class GroupControllerTest extends TestCase
{
    private $db;
    private $groupController;
    private $mockRequest;
    private $mockResponse;

    protected function setUp(): void
    {
        // Create a mock database connection
        $this->db = $this->createMock(PDO::class);
        
        // Create a mock group model
        $groupModel = $this->createMock(Group::class);
        $groupModel->method('create')->willReturn(1);
        $groupModel->method('findAll')->willReturn([
            ['id' => 1, 'name' => 'Test Group']
        ]);
        $groupModel->method('addMember')->willReturn(true);
        
        // Create a GroupController instance with the mock db
        $this->groupController = new GroupController($this->db);
        
        // Set a property to inject the mock group model
        $reflection = new ReflectionClass($this->groupController);
        $property = $reflection->getProperty('groupModel');
        $property->setAccessible(true);
        $property->setValue($this->groupController, $groupModel);
        
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

    public function testCreateGroup()
    {
        // Mock the request to return parsed body
        $this->mockRequest->method('getParsedBody')
            ->willReturn(['name' => 'Test Group']);
        
        // Call the method
        $response = $this->groupController->createGroup($this->mockRequest, $this->mockResponse);
        
        // Assert response is returned
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }

    public function testListGroups()
    {
        // Call the method
        $response = $this->groupController->listGroups($this->mockRequest, $this->mockResponse);
        
        // Assert response is returned
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }

    public function testJoinGroup()
    {
        // Mock the request to return user attribute
        $this->mockRequest->method('getAttribute')
            ->willReturn(['id' => 1]);
        
        // Call the method
        $response = $this->groupController->joinGroup($this->mockRequest, $this->mockResponse, ['group_id' => 1]);
        
        // Assert response is returned
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
    }
}