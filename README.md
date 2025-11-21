# Laravel JWT Blog API

A simple Laravel API for managing **users, posts, and comments** with **JWT authentication** and role-based permissions (admin/author). Includes caching for posts, search & filtering, and full CRUD operations.

---

## Table of Contents

-   [Features](#features)
-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Environment Setup](#environment-setup)
-   [Database Setup](#database-setup)
-   [Running the Project](#running-the-project)
-   [Running Tests](#running-tests)
-   [API Endpoints](#api-endpoints)
-   [License](#license)

---

## Features

-   JWT Authentication (login, register, logout)
-   Role-based access (`admin` / `author`)
-   CRUD for Posts
-   CRUD for Comments
-   Post caching
-   Search and filter posts by title, category, author, and date
-   Paginated responses
-   API responses in JSON

---

## Requirements

-   PHP >= 8.2
-   Composer
-   MySQL / MariaDB
-   Node.js & NPM (optional if you need Laravel Mix)
-   Laravel >= 11
-   [Tymon JWT Auth package](https://jwt-auth.readthedocs.io/en/develop/)

---

## Installation

1. Clone the repository:

```bash
git clone https://github.com/MohamedSalamaMousa/Blog_Task.git
cd Blog_Task

```

2. Install dependencies:

```bash
composer install
```

3. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Generate JWT secret key:

```bash
php artisan jwt:secret
```

---

## Environment Setup

Edit your `.env` file to configure database and other settings:

```env
APP_NAME=LaravelJWTBlog
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_jwt_blog
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
```

---

## Database Setup

1. Create a database in MySQL (e.g., `laravel_jwt_blog`).
2. Run migrations:

```bash
php artisan migrate
```

3. (Optional) Seed the database with test data:

```bash
php artisan db:seed
```

---

## Running the Project

Start the local server:

```bash
php artisan serve
```

The API will be accessible at:

```
http://127.0.0.1:8000/api
```

---

## Live Deployment

تم نشر المشروع بنجاح على استضافة **Hostinger**، ويمكن الوصول إلى النسخة الحية من الـ API من خلال الرابط التالي:

🔗 **Live API URL:**  
https://blog.qargoquote.com/api

## Running Tests

You can write and run **feature or unit tests** using PHPUnit:

```bash
php artisan test
```

This will execute all tests in `tests/Feature` and `tests/Unit`.

---

## API Documentation (Postman)

تم إعداد توثيق كامل للـ API على Postman ويمكنك زيارته من الرابط التالي:

🔗 **Postman Docs:**  
https://documenter.getpostman.com/view/22632135/2sB3WyLxFv

## API Endpoints

### Authentication

| Method | Endpoint        | Description                 |
| ------ | --------------- | --------------------------- |
| POST   | `/api/register` | Register a new user         |
| POST   | `/api/login`    | Login and receive JWT token |
| GET    | `/api/user`     | Get authenticated user info |
| POST   | `/api/logout`   | Logout and invalidate JWT   |

### Posts

| Method | Endpoint             | Description                                             |
| ------ | -------------------- | ------------------------------------------------------- |
| GET    | `/api/posts`         | Get paginated list of posts (cached)                    |
| GET    | `/api/posts/{id}`    | Get single post with author & comments                  |
| GET    | `/api/search_filter` | Search/filter posts by title, category, author, or date |
| POST   | `/api/posts`         | Create a new post (author/admin)                        |
| PUT    | `/api/posts/{id}`    | Update post (only author owns post or admin)            |
| DELETE | `/api/posts/{id}`    | Delete post (only author owns post or admin)            |

### Comments

| Method | Endpoint                   | Description             |
| ------ | -------------------------- | ----------------------- |
| POST   | `/api/posts/{id}/comments` | Add a comment to a post |

---

### Using the API

1. **Register a user** via `/api/register`.
2. **Login** via `/api/login` to get a token:

```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJh..."
}
```

3. **Add the token** in request headers for protected routes:

```
Authorization: Bearer <token>
```

4. Access protected endpoints like `/api/posts`, `/api/posts/{id}`, `/api/logout`.

---

## License

This project is open-source and free to use under the MIT License.
