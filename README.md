# Chat Application API Documentation

  

Here's how to interact with my Chat Application using **curl** in the command line. The API allows you to create users, create groups, send messages, and more.


---
---

## **To Note:**

The project contains a file named **Client.html**, unfortunately this file is not functional and just looks pretty as I was unable to get it to function properly within the time frame due to my schedule, I apologise and had I been more free I would have put in as much effort as possible to get this functional. I felt this was necessary to clarify In hopes that this will not be counted against me in my application in any way.

---
---

  

## **Base URL**

All API endpoints are relative to the base URL:
(In my case it was port 80, for you it will probably be different)

  

```

http://localhost:80/chat-app/public

```

  
---
---

  

## **Creating a User**

To create a new user, send a **POST** request to the **/users** endpoint with a JSON payload containing the **username**.

  

### **Request**

```bash

curl -X POST http://localhost:80/chat-app/public/users \

     -H "Content-Type: application/json" \

     -d "{\"username\": \"john_doe\"}" \

     -v

```

  

### **Response**

- **Status Code**: `201 Created`

- **Response Body**:

```json

  {

      "user_id": 1,

      "token": "random_generated_token"

  }

```

  

### **Explanation**

- The **user_id** is the ID of the newly created user.

- The **token** is a unique authentication token for the user. Use this token in the **Authorization** header for protected routes.

  
---
---

  

## **Creating a Group**

To create a new group, send a **POST** request to the **/groups** endpoint with a JSON payload containing the **name** of the group. You must include the **Authorization** header with the user token.

  

### **Request**

```bash

curl -X POST http://localhost:80/chat-app/public/groups \

     -H "Content-Type: application/json" \

     -H "Authorization: <user_token>" \

     -d "{\"name\": \"Test Group\"}" \

     -v

```

  

### **Response**

- **Status Code**: `201 Created`

- **Response Body**:

```json

  {

      "group_id": 1

  }

```

  

### **Explanation**

- The **group_id** is the ID of the newly created group.

- Only authenticated users (with a valid token) can create groups.

  
---
---

  

## **Listing All Groups**

To list all groups, send a **GET** request to the **/groups** endpoint. You must include the **Authorization** header with the user token.

  

### **Request**

```bash

curl -X GET http://localhost:80/chat-app/public/groups \

     -H "Authorization: <user_token>" \

     -v

```

  

### **Response**

- **Status Code**: `200 OK`

- **Response Body**:

```json

  [

      {

          "id": 1,

          "name": "Test Group"

      }

  ]

```

  

### **Explanation**

- The response is an array of groups, each containing the **id** and **name** of the group.

  
---
---

  

## **Joining a Group**

To join a group, send a **POST** request to the **/groups/{group_id}/join** endpoint. You must include the **Authorization** header with the user token.

  

### **Request**

```bash

curl -X POST http://localhost:80/chat-app/public/groups/1/join \

     -H "Content-Type: application/json" \

     -H "Authorization: <user_token>" \

     -v

```

  

### **Response**

- **Status Code**: `200 OK`

- **Response Body**:

```json

  {

      "success": true

  }

```

  

### **Explanation**

- The user associated with the **Authorization** token is added to the group with ID **1**.

  
---
---

  

## **Sending a Message**

To send a message to a group, send a **POST** request to the **/messages** endpoint with a JSON payload containing the **user_id**, **group_id**, and **message**. You must include the **Authorization** header with the user token.

  

### **Request**

```bash

curl -X POST http://localhost:80/chat-app/public/messages \

     -H "Content-Type: application/json" \

     -H "Authorization: <user_token>" \

     -d "{\"user_id\": 1, \"group_id\": 1, \"message\": \"Hello, World!\"}" \

     -v

```

  

### **Response**

- **Status Code**: `201 Created`

- **Response Body**:

```json

  {

      "message_id": 1

  }

```

  

### **Explanation**

- The **message_id** is the ID of the newly created message.

- The **user_id** must match the user associated with the **Authorization** token.

  
---
---

  

## **Listing Messages in a Group**

To list all messages in a group, send a **GET** request to the **/groups/{group_id}/messages** endpoint. You must include the **Authorization** header with the user token.

  

### **Request**

```bash

curl -X GET http://localhost:80/chat-app/public/groups/1/messages \

     -H "Authorization: <user_token>" \

     -v

```

  

### **Response**

- **Status Code**: `200 OK`

- **Response Body**:

```json

  [

      {

          "id": 1,

          "user_id": 1,

          "group_id": 1,

          "message": "Hello, World!",

          "timestamp": "2023-10-01 12:34:56"

      }

  ]

```

  

### **Explanation**

- The response is an array of messages, each containing:

  - **id**: The message ID.

  - **user_id**: The ID of the user who sent the message.

  - **group_id**: The ID of the group the message belongs to.

  - **message**: The message content.

  - **timestamp**: The timestamp when the message was sent.

  
---
---


## **Potential Error Responses**

If something goes wrong, you might receive one of the following error responses:

  

#### **Invalid JSON**:

- **Status Code**: `400 Bad Request`

- **Response Body**:

```json

  {

      "error": "Invalid JSON"

  }

```

  

#### **Missing or Invalid Fields**:

- **Status Code**: `400 Bad Request`

- **Response Body**:

```json

  {

      "error": "Username is required"

  }

```

  

#### **Unauthorized Access**:

- **Status Code**: `401 Unauthorized`

- **Response Body**:

```json

  {

      "error": "Unauthorized"

  }

```

  

#### **Resource Not Found**:

- **Status Code**: `404 Not Found`

- **Response Body**:

```json

  {

      "error": "Group not found"

  }

 ```

  

#### **Conflict (e.g., Duplicate Username)**:

- **Status Code**: `409 Conflict`

- **Response Body**:

```json

  {

      "error": "Username is already taken"

  }

```

  

#### **Internal Server Error**:

- **Status Code**: `500 Internal Server Error`

- **Response Body**:

```json

  {

      "error": "An error occurred while creating the user"

  }

```

