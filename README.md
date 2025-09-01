<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

Yii 2 Advanced Project Template is a skeleton [Yii 2](https://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![build](https://github.com/yiisoft/yii2-app-advanced/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-advanced/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```

# Advanced Bookshop System - Yii2 Advanced Template

A comprehensive bookshop system built on Yii2 Advanced Template with user roles, book management, and external API integration.

## Features

- **User Roles**: Readers, Authors, and Admins
- **Book Management**: Create, edit, delete books and chapters
- **Reading System**: Free preview (10 pages) for guests, full access for registered users
- **External API Integration**: Google Books API for real book data
- **Email Verification**: Required email confirmation upon signup
- **Progress Tracking**: Monitor user reading progress
- **Role-Based Access Control**: Authors can only manage their own books

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- XAMPP (for local development)

## Installation & Setup

### 1. Database Setup

First, create your MySQL database:

```sql
CREATE DATABASE advanced_book CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

The database configuration is already set up in:
- `environments/dev/common/config/main-local.php` (development)
- `environments/prod/common/config/main-local.php` (production)

Both are configured to use:
- Host: `localhost`
- Database: `advanced_book`
- Username: `root`
- Password: `` (empty for XAMPP default)

### 2. Initialize Yii2 Application

Run the initialization command to set up your environment:

```bash
# For Windows (PowerShell/Command Prompt)
php init

# Select 'dev' environment when prompted
# This will copy configuration files from environments/dev/ to your project root
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Run Database Migrations

```bash
# Navigate to your project root
cd /path/to/your/bookshop

# Run migrations to create all necessary tables
php yii migrate

# This will create:
# - user table (with role column)
# - books table
# - book_chapters table
# - user_reading_progress table
```

### 5. Create Admin User

```bash
# Create your first admin user
php yii user/create-admin

# Follow the prompts to set username, email, and password
```

### 6. Configure Email Settings

Edit `common/config/main-local.php` to configure email:

```php
'mailer' => [
    'class' => \yii\symfonymailer\Mailer::class,
    'viewPath' => '@common/mail',
    'useFileTransport' => false, // Set to false for real emails
    
    // SMTP Configuration (example for Gmail)
    'transport' => [
        'scheme' => 'smtps',
        'host' => 'smtp.gmail.com',
        'username' => 'your-email@gmail.com',
        'password' => 'your-app-password',
        'port' => 465,
    ],
],
```

### 7. Configure Google Books API

Edit `common/config/params.php`:

```php
'googleBooksApiKey' => 'YOUR_GOOGLE_BOOKS_API_KEY',
```

To get a Google Books API key:
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google Books API
4. Create credentials (API Key)
5. Copy the key to your configuration

### 8. Web Server Configuration

#### For XAMPP (Apache):
1. Ensure your project is in `htdocs/bookshop/`
2. Access via: `http://localhost/bookshop/frontend/web/`
3. Backend: `http://localhost/bookshop/backend/web/`

#### For XAMPP (Nginx):
1. Configure virtual host pointing to `frontend/web/`
2. Set document root to your project's `frontend/web/` directory

### 9. Set File Permissions

```bash
# Set write permissions for runtime and web/assets directories
chmod -R 777 runtime/
chmod -R 777 web/assets/
chmod -R 777 frontend/web/assets/
chmod -R 777 backend/web/assets/
```

## How It Works

### API Integration System

The bookshop integrates with Google Books API through the `BookApiService` class:

#### 1. **Service Architecture**
```php
common/services/BookApiService.php
```
This service handles all external API interactions and provides methods for:
- Searching books by query
- Fetching book details by ISBN
- Getting book information by Google Books ID
- Importing books into your local database

#### 2. **API Methods**

**Search Books:**
```php
$apiService = Yii::$app->bookApiService;
$books = $apiService->searchBooks('Harry Potter', 20);
```

**Get Book by ISBN:**
```php
$book = $apiService->getBookByIsbn('9780747532699');
```

**Import Book:**
```php
$importedBook = $apiService->importBook($bookData, $authorId);
```

#### 3. **Data Flow**
1. User searches for a book on frontend
2. Frontend controller calls `BookApiService::searchBooks()`
3. Service makes HTTP request to Google Books API
4. Response is parsed and formatted
5. Results are displayed to user
6. User can import books (if authorized)

#### 4. **API Configuration**
- Base URL: `https://www.googleapis.com/books/v1`
- API Key: Configured in `common/config/params.php`
- Rate Limits: Google Books API allows 1,000 requests per day (free tier)

### Email Verification System

The system uses Yii2's built-in email verification with Symfony Mailer:

#### 1. **Email Flow**
1. User signs up with email
2. System generates verification token
3. Verification email is sent via configured mailer
4. User clicks link in email
5. Account is verified and activated

#### 2. **Mailer Configuration**
```php
'mailer' => [
    'class' => \yii\symfonymailer\Mailer::class,
    'viewPath' => '@common/mail',
    'useFileTransport' => false,
    'transport' => [
        'scheme' => 'smtps',
        'host' => 'smtp.gmail.com',
        'username' => 'your-email@gmail.com',
        'password' => 'your-app-password',
        'port' => 465,
    ],
],
```

#### 3. **Email Templates**
Located in `common/mail/`:
- `emailVerify-html.php` - HTML version of verification email
- `emailVerify-text.php` - Plain text version
- `layouts/main-html.php` - HTML email layout
- `layouts/main-text.php` - Text email layout

#### 4. **Verification Process**
1. **Token Generation**: Random 32-character string
2. **Token Storage**: Saved in `user.verification_token` field
3. **Email Sending**: Uses configured SMTP settings
4. **Verification**: User clicks link with token
5. **Activation**: Token is cleared, status set to active

#### 5. **Security Features**
- Tokens expire after 12 hours (configurable)
- Tokens are unique per user
- Verification required before login
- Password reset functionality included

### User Role System

#### 1. **Role Definitions**
```php
const ROLE_READER = 'reader';    // Can read books, limited preview
const ROLE_AUTHOR = 'author';    // Can create/edit own books
const ROLE_ADMIN = 'admin';      // Full system access
```

#### 2. **Access Control**
- **Readers**: View books, read content, track progress
- **Authors**: Manage own books, add chapters, edit content
- **Admins**: Full CRUD access to all books and users

#### 3. **Permission Methods**
```php
$user->hasRole('author');    // Check specific role
$user->isAdmin();           // Check if admin
$user->isAuthor();          // Check if author
$user->isReader();          // Check if reader
```

### Reading System

#### 1. **Preview Logic**
- Guests and unverified users: 10 pages free
- Verified users: Full access to all books
- Progress tracking via `UserReadingProgress` model

#### 2. **Page Calculation**
- Pages calculated based on word count (250 words per page)
- Progress stored per chapter and page
- Preview limit enforced in `BookController::actionRead()`

## Usage

### Frontend (Public)
- Browse books: `/book/index`
- View book details: `/book/view?id=1`
- Read book: `/book/read?id=1&chapter=1`
- Search books: Use search form on book listing page

### Backend (Admin)
- Manage books: `/book/index`
- Create book: `/book/create`
- Edit book: `/book/update?id=1`
- Add chapters: `/book/add-chapter?id=1`

### Console Commands
```bash
# User management
php yii user/create-admin          # Create admin user
php yii user/list                 # List all users
php yii user/change-role username # Change user role
php yii user/toggle-status username # Toggle user status

# Database
php yii migrate                   # Run migrations
php yii migrate/down             # Rollback last migration
php yii migrate/history          # Show migration history
```

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify MySQL is running in XAMPP
   - Check database name is `advanced_book`
   - Ensure username/password are correct

2. **Migration Errors**
   - Check database exists and is accessible
   - Verify all previous migrations completed successfully
   - Check file permissions for runtime directory

3. **Email Not Sending**
   - Verify `useFileTransport` is set to `false`
   - Check SMTP credentials and settings
   - Ensure port 465/587 is not blocked by firewall

4. **API Errors**
   - Verify Google Books API key is valid
   - Check API quota limits
   - Ensure internet connection is available

### Debug Mode
Enable debug mode in `common/config/main-local.php`:
```php
'components' => [
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
            ],
        ],
    ],
],
```

## Security Considerations

1. **API Keys**: Never commit API keys to version control
2. **Email Credentials**: Use environment variables for sensitive data
3. **User Input**: All user input is validated and sanitized
4. **Access Control**: Role-based permissions enforced at model and controller levels
5. **SQL Injection**: Protected by Yii2's ActiveRecord and prepared statements

## Performance Optimization

1. **Database Indexes**: Added on frequently queried columns
2. **Caching**: Configure cache components for better performance
3. **API Caching**: Consider caching API responses to reduce external calls
4. **Image Optimization**: Compress cover images for faster loading

## Contributing

1. Follow Yii2 coding standards
2. Add tests for new functionality
3. Update documentation for API changes
4. Use meaningful commit messages

## License

This project is licensed under the MIT License.
