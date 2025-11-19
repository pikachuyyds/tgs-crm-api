# TGS Backend Developer Assignment – Customer Management API

## Overview

This is a small backend API built with Laravel 12 that allows users to register, login, and manage their customers. Only authenticated users can view or modify their customers.

---

## Installation

1. Clone the repository:

```powershell
git clone 'https://github.com/pikachuyyds/tgs-crm-api.git'
cd tgs-crm-api
```

2. Install dependencies

```powershell
composer install
```

3. Create .env and configure database

```powershell
cp .env.example .env
```

4. Run migration for database

```powershell
php artisan migrate
```

5. Start server

```powershell
php artisan serve
```

---

### API Endpoints

-   baseUrl is http://127.0.0.1:8000/api

1. Register (POST baseUrl/register)

```git
Headers: Accept: application/json
```

Body (JSON):

```json
{
    "name": "User3",
    "email": "user3@example.com",
    "password": "user123",
    "password_confirmation": "user123"
}
```

-   Response returns user data and JWT token
-   Save the token in Postman environment variable jwt_token
-   Token expires after 60 minutes, login again to refresh

2. Login (POST baseUrl/login)

```git
Headers: Accept: application/json
```

Body (JSON):

```json
{
    "email": "user3@example.com",
    "password": "user123"
}
```

-   Response returns the authenticated user data and JWT token
-   You can choose to save this token or register token, both work

3. Get customers (GET baseUrl/customers)

```git
Headers:
    Accept: application/json
    Authorization: Bearer {{jwt_token}}
```

-   Response returns list of customers of the login user

4. Create customer (POST baseUrl/customers)

```git
Headers:
    Accept: application/json
    Authorization: Bearer {{jwt_token}}
```

Body (JSON):

```json
{
    "name": "Customer 1",
    "email": "customer1@example.com",
    "phone": "1234567890"
}
```

-   Response returns the details of created customers and belongs to which user

5. Update customer (PUT baseUrl/customers/1)

```git
Headers:
    Accept: application/json
    Authorization: Bearer {{jwt_token}}
    Content-Type: application/json
```

Body (JSON):

```json
{
    "name": "Customer 1 for user 3",
    "email": "customer1for3@example.com",
    "phone": "1234567888"
}
```

-   1 is the customer id
-   Include content-type to tell Laravel to parse body as JSON
-   Response returns updated details of customer

6. Delete customer (DELETE baseUrl/customers/1)

```git
Headers:
    Accept: application/json
    Authorization: Bearer {{jwt_token}}
```

-   Response returns message of 'Customer deleted'

---

### Bonus/Optional Features

-   Pagination
    -   can verify via get customer response per page is 10
-   JWT token validation
    -   all customer routes require JWT token in Authorization header
-   Postman files
    -   [Postman collection](https://lunar-astronaut-635559.postman.co/workspace/Hoi-Yi's-Workspace~75c6a6d1-ded8-487a-84ee-b3510683a656/collection/50161525-20abf962-1e94-440f-b2b1-60f75c34bf6e?action=share&creator=50161525&active-environment=50161525-44684709-0774-4bf7-aecc-5903bec62bff)

---

### Step by step (for my own reference)

1. github create repo called tgs-crm-api
2. gitclone and open folder
3. composer create-project laravel/laravel .
4. php artisan key:generate
5. php artisan migrate
6. composer require tymon/jwt-auth
7. php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
8. php artisan jwt:secret
9. php artisan make:controller AuthController
10. update config/auth.php to use api jwt
11. update bootstrap/app.php
12. php artisan make:model Customer -mcr
13. edit the migration of customer and php artisan migrate
14. update AuthController and CustomerController
15. update route/api.php
16. setup postman with baseurl
17. run register request
18. save the token in environment variable
19. run login request
20. update user model to define relationship
21. update customer model for mass assignment and define relationship
22. run create customer request with headers token
23. run get customer request with headers token
24. run update customer request with headers token
25. run delete customer request with headers token
26. run get customer request to ensure deleted
27. update readme.md
