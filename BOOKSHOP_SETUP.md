# Bookshop System Setup Guide

This guide will help you set up the complete bookshop system built with Yii2 Advanced Template.

## Features

- **User Roles**: Readers, Authors, and Admins
- **Book Management**: Create, edit, delete books and chapters
- **Reading System**: 10-page free preview with signup requirement
- **External API Integration**: Google Books API for importing real book data
- **Email Verification**: Automatic email confirmation on signup
- **Responsive Design**: Modern UI for both frontend and backend

## Prerequisites

- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 or higher
- Composer
- Git

## Installation Steps

### 1. Clone and Setup Project

```bash
# Navigate to your XAMPP htdocs directory
cd /c/xampp/htdocs

# Clone the project (if using git) or extract the files
# Navigate to the project directory
cd bookshop

# Install dependencies
composer install
```

### 2. Database Setup

1. **Create Database**: Open phpMyAdmin and create a new database named `bookshop`
2. **Configure Database**: Update `common/config/main-local.php` with your database credentials:

```php
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=bookshop',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
];
```

3. **Run Migrations**: Execute the database migrations:

```bash
# Navigate to the project root
cd /c/xampp/htdocs/bookshop

# Run migrations
php yii migrate
```

### 3. Google Books API Setup

1. **Get API Key**: 
   - Go to [Google Cloud Console](https://console.developers.google.com/)
   - Create a new project or select existing one
   - Enable the Google Books API
   - Create credentials (API Key)

2. **Configure API Key**: Update `common/config/params.php`:

```php
'googleBooksApiKey' => 'YOUR_ACTUAL_API_KEY_HERE',
```

### 4. Email Configuration

1. **Configure Email Settings**: Update `common/config/main-local.php`:

```php
<?php
return [
    'components' => [
        'mailer' => [
            'class' => 'yii\symfonymailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false, // Set to false for production
            'transport' => [
                'class' => 'Symfony\Component\Mailer\Transport\SmtpTransport',
                'host' => 'smtp.gmail.com', // Your SMTP host
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'your-email@gmail.com',
                'password' => 'your-app-password',
            ],
        ],
    ],
];
```

### 5. Web Server Configuration

1. **Apache Virtual Host** (Optional but recommended):

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/bookshop/frontend/web"
    ServerName bookshop.local
    
    <Directory "C:/xampp/htdocs/bookshop/frontend/web">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

2. **Update Hosts File**: Add `127.0.0.1 bookshop.local` to `C:\Windows\System32\drivers\etc\hosts`

### 6. Initialize the Application

```bash
# Run the init script
php init

# Choose your environment (dev/prod)
# Set database connection details
# Set cookie validation key
```

### 7. Set File Permissions

```bash
# Set write permissions for runtime and web/assets directories
chmod -R 777 runtime/
chmod -R 777 web/assets/
chmod -R 777 backend/web/assets/
chmod -R 777 frontend/web/assets/
```

## Usage

### Frontend (Public Bookshop)

- **URL**: `http://bookshop.local` or `http://localhost/bookshop/frontend/web`
- **Features**:
  - Browse books
  - Read first 10 pages for free
  - Search books
  - User registration and login

### Backend (Admin Panel)

- **URL**: `http://bookshop.local/backend` or `http://localhost/bookshop/backend/web`
- **Features**:
  - Manage books and chapters
  - Import books from Google Books API
  - User management
  - Content management

### Console Commands

```bash
# Create admin user
php yii user/create-admin

# Import books from API
php yii book/import

# Check system status
php yii system/status
```

## User Roles and Permissions

### Reader
- Browse and search books
- Read first 10 pages for free
- Must sign up to continue reading
- Email verification required

### Author
- Create and edit their own books
- Add chapters to their books
- Delete their own books
- Import books from external APIs

### Admin
- Full access to all features
- Manage all users and books
- System configuration
- Analytics and reports

## API Integration

The system integrates with Google Books API to:
- Search for books by title, author, or ISBN
- Import book metadata and covers
- Fetch real book information

### API Endpoints Used
- `GET /volumes` - Search books
- `GET /volumes/{id}` - Get book details
- ISBN search support

## Customization

### Adding New Book APIs
1. Create new service class in `common/services/`
2. Implement the `BookApiServiceInterface`
3. Update configuration to use new service

### Modifying Preview Limits
Update `common/config/params.php`:
```php
'previewPageLimit' => 15, // Change from 10 to 15 pages
```

### Custom Email Templates
Modify templates in `common/mail/` directory

## Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Check database credentials in `common/config/main-local.php`
   - Ensure MySQL service is running in XAMPP

2. **Migration Errors**:
   - Check database exists and is accessible
   - Verify PHP version compatibility

3. **Email Not Sending**:
   - Check SMTP configuration
   - Verify email credentials
   - Check spam folder

4. **API Integration Issues**:
   - Verify Google Books API key
   - Check API quotas and limits
   - Ensure internet connectivity

### Debug Mode

Enable debug mode in `environments/dev/`:
```php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
```

## Security Considerations

1. **API Keys**: Never commit API keys to version control
2. **File Uploads**: Validate and sanitize uploaded files
3. **User Input**: Always use Yii's built-in validation
4. **Database**: Use prepared statements (Yii handles this automatically)
5. **Sessions**: Configure secure session settings

## Performance Optimization

1. **Database Indexes**: Already configured in migrations
2. **Caching**: Enable Redis/Memcached for better performance
3. **Asset Optimization**: Use Yii's asset compression
4. **CDN**: Serve static assets from CDN in production

## Support

For issues and questions:
1. Check the troubleshooting section above
2. Review Yii2 documentation
3. Check error logs in `runtime/logs/`
4. Enable debug mode for detailed error information

## License

This project uses the BSD-3-Clause license. See LICENSE.md for details.
