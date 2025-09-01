@echo off
echo ========================================
echo Advanced Bookshop System Setup
echo ========================================
echo.

echo Starting setup process...
echo.

echo 1. Checking PHP installation...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP is not installed or not in PATH
    echo Please install PHP or add it to your system PATH
    pause
    exit /b 1
)
echo PHP found ✓
echo.

echo 2. Checking Composer installation...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Composer is not installed or not in PATH
    echo Please install Composer or add it to your system PATH
    pause
    exit /b 1
)
echo Composer found ✓
echo.

echo 3. Checking if database exists...
echo Please ensure you have created the 'advanced_book' database in MySQL
echo You can do this by:
echo - Opening phpMyAdmin at http://localhost/phpmyadmin
echo - Creating a new database named 'advanced_book'
echo - Setting charset to utf8mb4_unicode_ci
echo.
pause

echo 4. Initializing Yii2 application...
echo Please select 'dev' environment when prompted
php init
if %errorlevel% neq 0 (
    echo ERROR: Failed to initialize Yii2 application
    pause
    exit /b 1
)
echo Yii2 initialization completed ✓
echo.

echo 5. Installing dependencies...
composer install
if %errorlevel% neq 0 (
    echo ERROR: Failed to install dependencies
    pause
    exit /b 1
)
echo Dependencies installed ✓
echo.

echo 6. Running database migrations...
php yii migrate
if %errorlevel% neq 0 (
    echo ERROR: Failed to run migrations
    echo Please check your database connection
    pause
    exit /b 1
)
echo Database migrations completed ✓
echo.

echo 7. Creating admin user...
echo Please follow the prompts to create your admin account
php yii user/create-admin
if %errorlevel% neq 0 (
    echo ERROR: Failed to create admin user
    pause
    exit /b 1
)
echo Admin user created ✓
echo.

echo ========================================
echo Setup completed successfully!
echo ========================================
echo.
echo Next steps:
echo 1. Configure your email settings in common/config/main-local.php
echo 2. Add your Google Books API key in common/config/params.php
echo 3. Access your application:
echo    - Frontend: http://localhost/bookshop/frontend/web/
echo    - Backend: http://localhost/bookshop/backend/web/
echo.
echo For detailed configuration instructions, see README.md
echo.
pause
