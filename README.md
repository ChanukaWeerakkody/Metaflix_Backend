      # Metaflix Backend

A Laravel-based REST API backend for a movie streaming platform with JWT authentication, multi-language support, and comprehensive movie management.

## Features

- 🎬 Movie management (CRUD operations)
- 🎭 Movie roles and cast management
- 🎥 Movie trailers support
- 🌍 Multi-language support
- 🔐 JWT authentication
- 📂 Category management
- 👥 User roles and permissions
- 🔒 Laravel Sanctum API tokens

## Tech Stack

- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Authentication**: JWT (tymon/jwt-auth) + Laravel Sanctum
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **Testing**: PHPUnit

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM (for frontend assets)
- SQLite/MySQL/PostgreSQL

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd Metaflix_Backend
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database

Edit `.env` file:

```env
DB_CONNECTION=sqlite
# Or for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=metaflix
# DB_USERNAME=root
# DB_PASSWORD=
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Generate JWT secret

```bash
php artisan jwt:secret
```

### 7. Start development server

```bash
php artisan serve
```

API will be available at: `http://localhost:8000`

## Database Models

- **User** - User accounts with authentication
- **Role** - User roles (admin, user, etc.)
- **Permission** - Access permissions
- **Movie** - Movie information
- **MovieRole** - Cast and crew roles
- **MovieTrailer** - Movie trailers
- **Category** - Movie categories/genres
- **Language** - Supported languages

## API Endpoints

### Authentication

```
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/auth/me
```

### Movies

```
GET    /api/movies
GET    /api/movies/{id}
POST   /api/movies
PUT    /api/movies/{id}
DELETE /api/movies/{id}
```

### Languages

```
GET    /api/languages
POST   /api/languages
PUT    /api/languages/{id}
DELETE /api/languages/{id}
```

## Development

### Run tests

```bash
php artisan test
```

### Code formatting

```bash
./vendor/bin/pint
```

### Run all dev services

```bash
composer dev
```

This starts: server, queue worker, logs, and Vite

## Project Structure

```
Metaflix_Backend/
├── App/
│   ├── Http/Controllers/    # API controllers
│   ├── Models/              # Eloquent models
│   └── Repositories/        # Repository pattern
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── routes/
│   ├── api.php             # API routes
│   └── web.php             # Web routes
└── tests/                  # PHPUnit tests
```

## Deployment

### AWS Deployment Options

1. **Elastic Beanstalk** (Recommended for beginners)

    ```bash
    eb init
    eb create metaflix-env
    eb deploy
    ```

2. **EC2 Manual Setup**
    - Launch EC2 instance
    - Install PHP 8.2, Composer, Nginx
    - Clone repo and run `composer install --optimize-autoloader --no-dev`
    - Configure Nginx to point to `/public`
    - Set up RDS for database

3. **Docker + ECS**
    - Build Docker image
    - Push to ECR
    - Deploy to ECS/Fargate

### Environment Variables for Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
DB_HOST=your-rds-endpoint
```

## Security

- JWT tokens for API authentication
- CORS configured via `fruitcake/php-cors`
- Environment variables for sensitive data
- SQL injection protection via Eloquent ORM

## License

MIT License

## Support

For issues and questions, please open an issue in the repository.
