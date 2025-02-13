# ToDo List API - Symfony 7

## Description
This API allows managing a task list (**ToDo List**) with the following features:
- **Create** a task (`POST /tasks`)
- **List** all tasks (`GET /tasks`)
- **Mark** a task as completed (`PATCH /tasks/{id}/complete`)
- **Delete** a task (`DELETE /tasks/{id}`)

---

## Installation & Configuration

### **Clone the project**
```sh
git clone https://github.com/HervillardLeo/api_toDoList
cd api_toDoList
```

### **Install dependencies**
```sh
composer install
```

### **Configure the database (SQLite)**

create the database and run migrations:
```sh
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### **Start the server**
```sh
symfony server:start
```
Or 

```sh
php -S 127.0.0.1:8000 -t public
```

The API will be available at **`http://127.0.0.1:8000`**.

---

##  **API Endpoints**

###  **List all tasks**
```
GET /tasks
```
**Response:**
```json
[
  {
    "id": 1,
    "title": "Test Task",
    "isCompleted": false,
    "createdAt": "2024-02-13T10:00:00+00:00"
  }
]
```

### **Create a task**
```
POST /tasks
```
**Request body:**
```json
{
  "title": "New task"
}
```
**Response:**
```json 
{
    "id": "1",
    "title": "New task",
    "completed": false,
    "createdAt": "2024-02-13T10:00:00+00:00"
}
```
###  **Mark a task as completed**
```
PATCH /tasks/{id}/complete
```
**Response:**
```json 
{
    "id": "1",
    "title": "New task",
    "completed": true,
    "createdAt": "2024-02-13T10:00:00+00:00"
}
```

### **Delete a task**
```
DELETE /tasks/{id}
```
**Response:** `204 No Content`

---

## **Tests**
Run unit and functional tests with:
```sh
php bin/phpunit
```

---

## **Technologies Used**
- Symfony 7.2
- PHP 8.2
- PHPUnit for testing

---

## **Future Improvements**
- Implement authentication (JWT)
- Add pagination for task listing
- Add stats for tasks 
- Add WebSockets with Mercure for real-time updates
- Add task queue with Messenger for background task

---


