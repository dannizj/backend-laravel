# Backend Laravel - API REST with JWT, Images and AI

RESTful API built with **Laravel 13**, **PHP 8.3** and **MySQL/MariaDB**.  
Includes **JWT** authentication, avatar and poster image uploads, article search, and article generation with **Artificial Intelligence (OpenAI GPT-4o-mini)**.

---

## 🚀 Features

- User authentication (registration and login with JWT)
- User profile and data update
- User avatar upload and retrieval
- Full articles CRUD (create, list, detail, search, update, delete)
- Poster image upload for articles
- Article search by title or content
- Automatic article generation with AI (OpenAI)
- Public and JWT-protected routes
- Structured JSON responses
- Relational database with migrations

---

## 🛠️ Tech Stack

| Technology | Version | Usage |
|------------|---------|-------|
| Laravel | 13.x | PHP Framework |
| PHP | 8.3 | Backend language |
| MySQL / MariaDB | 10.11 | Database |
| firebase/php-jwt | 7.x | JWT generation and verification |
| openai-php/client | 0.x | OpenAI integration |
| Laravel Storage | - | File management (avatars, posters) |

---

## 📋 Requirements

- PHP 8.3 or higher
- Composer
- MySQL or MariaDB (port 3306)
- Web server (Apache/Nginx) or `php artisan serve`
- PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`

---

## 📦 Installation

```bash
# 1. Clone the repository
git clone https://github.com/dannizj/backend-laravel.git
cd backend-laravel

# 2. Install PHP dependencies
composer install

# 3. Copy the environment file
cp .env.example .env

# 4. Generate the application key
php artisan key:generate

# 5. Configure the database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=backend_laravel
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Create the public storage symbolic link
php artisan storage:link

# 8. Start the development server
php artisan serve
```

The API will be available at: `http://127.0.0.1:8000/api`

---

## ⚙️ Environment Variables (.env)

Create your `.env` file based on `.env.example` and configure:

```env
APP_NAME=BackendLaravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backend_laravel
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=local

# Secret key to sign JWT tokens
JWT_SECRET=your_secret_key_here

# OpenAI key for AI article generation
OPENAI_API_KEY=sk-your_openai_key
```

> ⚠️ Never upload your `.env` file to the repository. It is included in `.gitignore`.

---

## 🔗 API Endpoints

### Users (Public)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/user/register` | Register new user |
| `POST` | `/api/user/login` | Login and get JWT token |
| `GET` | `/api/user/profile/{id}` | Get user profile by ID |
| `GET` | `/api/user/avatar/{file}` | Get user avatar image |

### Users (Protected - require JWT)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `PUT` | `/api/user/update` | Update authenticated user data |
| `POST` | `/api/user/upload` | Upload user avatar image |

### Articles (Public)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/article/items/{page}` | List paginated articles |
| `GET` | `/api/article/item/{id}` | Get article by ID |
| `GET` | `/api/article/user/{user_id}` | Articles by specific user |
| `GET` | `/api/article/search/{searchString}` | Search articles by title or content |
| `GET` | `/api/article/poster/{file}` | Get article poster image |

### Articles (Protected - require JWT)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/article/save` | Create a new article |
| `POST` | `/api/article/upload/{id}` | Upload poster image to an article |
| `PUT` | `/api/article/update/{id}` | Update an article (author only) |
| `DELETE` | `/api/article/delete/{id}` | Delete an article (author only) |
| `POST` | `/api/article/generate/{theme}` | Generate article with AI based on theme |

---

## 🔐 JWT Authentication

The API uses **JSON Web Tokens (JWT)** to authenticate requests.

1. Login at `POST /api/user/login` with email and password.
2. You will receive a JWT token in the response.
3. To access protected routes, send the token in the header:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## 🖼️ File Uploads

Two storage disks are configured in `config/filesystems.php`:

- **avatars**: `storage/app/avatars/` — User profile images
- **posters**: `storage/app/posters/` — Article poster images

These folders are included in `.gitignore` and are not uploaded to the repository.

---

## 🤖 AI Article Generation

The endpoint `POST /api/article/generate/{theme}` uses **OpenAI GPT-4o-mini** to automatically create the title and content of an article based on a theme. The generated article is validated and saved associated with the authenticated user.

Requires configuring `OPENAI_API_KEY` in your `.env` file.

---

## 📁 Project Structure

```
backend-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ArticleController.php
│   │   │   └── UserController.php
│   │   ├── Middleware/
│   │   │   └── JwtMiddleware.php
│   │   └── ...
│   ├── Helpers/
│   │   └── JwtAuth.php
│   ├── Models/
│   │   ├── Article.php
│   │   └── User.php
│   └── ...
├── config/
│   └── filesystems.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
├── public/
├── storage/
│   └── app/
│       ├── avatars/      (ignored in git)
│       └── posters/      (ignored in git)
├── .env.example
├── .gitignore
├── composer.json
└── README.md
```

---

## 🧪 Tests

The project includes configuration for testing with Pest/PHPUnit:

```bash
php artisan test
```

---

## 📄 License

This project is under the **MIT** license. See the `LICENSE` file for more information.

---

## 👨‍💻 Author

**Danni ZJ**  
Backend Developer — PHP, Laravel, SQL, REST APIs

- GitHub: [@dannizj](https://github.com/dannizj)
- Repository: [backend-laravel](https://github.com/dannizj/backend-laravel)

---

## 📌 Version History

| Version | Description |
|---------|-------------|
| v1.0.0 | AI article generation and final portfolio version |
| v0.8.0 | Full articles CRUD with posters |
| v0.7.0 | Avatar endpoints (upload and view) |
| v0.6.0 | User profile by ID |
| v0.5.0 | User update protected by JWT |
| v0.4.0 | JWT middleware for protected routes |
| v0.3.0 | Login with JWT token generation |
| v0.2.1 | Legacy SQL schema cleanup |
| v0.2.0 | User registration with validation and password hashing |
| v0.1.0 | MySQL connection and API routes |
| v0.0.0 | Initial Laravel 13 skeleton |
