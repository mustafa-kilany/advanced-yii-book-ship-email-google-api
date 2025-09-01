# Advanced Bookshop System Setup Script
# Run this script in PowerShell with execution policy set to allow scripts

Write-Host "========================================" -ForegroundColor Green
Write-Host "Advanced Bookshop System Setup" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Write-Host "Starting setup process..." -ForegroundColor Yellow
Write-Host ""

# Check PHP installation
Write-Host "1. Checking PHP installation..." -ForegroundColor Cyan
try {
    $phpVersion = php --version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "PHP found ✓" -ForegroundColor Green
        Write-Host $phpVersion[0] -ForegroundColor Gray
    } else {
        throw "PHP not found"
    }
} catch {
    Write-Host "ERROR: PHP is not installed or not in PATH" -ForegroundColor Red
    Write-Host "Please install PHP or add it to your system PATH" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}
Write-Host ""

# Check Composer installation
Write-Host "2. Checking Composer installation..." -ForegroundColor Cyan
try {
    $composerVersion = composer --version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Composer found ✓" -ForegroundColor Green
        Write-Host $composerVersion[0] -ForegroundColor Gray
    } else {
        throw "Composer not found"
    }
} catch {
    Write-Host "ERROR: Composer is not installed or not in PATH" -ForegroundColor Red
    Write-Host "Please install Composer or add it to your system PATH" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}
Write-Host ""

# Database setup reminder
Write-Host "3. Database setup reminder..." -ForegroundColor Cyan
Write-Host "Please ensure you have created the 'advanced_book' database in MySQL" -ForegroundColor Yellow
Write-Host "You can do this by:" -ForegroundColor Yellow
Write-Host "- Opening phpMyAdmin at http://localhost/phpmyadmin" -ForegroundColor Gray
Write-Host "- Creating a new database named 'advanced_book'" -ForegroundColor Gray
Write-Host "- Setting charset to utf8mb4_unicode_ci" -ForegroundColor Gray
Write-Host ""
Read-Host "Press Enter when database is ready"

# Initialize Yii2 application
Write-Host "4. Initializing Yii2 application..." -ForegroundColor Cyan
Write-Host "Please select 'dev' environment when prompted" -ForegroundColor Yellow
try {
    php init
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Yii2 initialization completed ✓" -ForegroundColor Green
    } else {
        throw "Initialization failed"
    }
} catch {
    Write-Host "ERROR: Failed to initialize Yii2 application" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}
Write-Host ""

# Install dependencies
Write-Host "5. Installing dependencies..." -ForegroundColor Cyan
try {
    composer install
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Dependencies installed ✓" -ForegroundColor Green
    } else {
        throw "Dependency installation failed"
    }
} catch {
    Write-Host "ERROR: Failed to install dependencies" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}
Write-Host ""

# Run database migrations
Write-Host "6. Running database migrations..." -ForegroundColor Cyan
try {
    php yii migrate
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Database migrations completed ✓" -ForegroundColor Green
    } else {
        throw "Migrations failed"
    }
} catch {
    Write-Host "ERROR: Failed to run migrations" -ForegroundColor Red
    Write-Host "Please check your database connection" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}
Write-Host ""

# Create admin user
Write-Host "7. Creating admin user..." -ForegroundColor Cyan
Write-Host "Please follow the prompts to create your admin account" -ForegroundColor Yellow
try {
    php yii user/create-admin
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Admin user created ✓" -ForegroundColor Green
    } else {
        throw "Admin user creation failed"
    }
} catch {
    Write-Host "ERROR: Failed to create admin user" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}
Write-Host ""

# Setup completion
Write-Host "========================================" -ForegroundColor Green
Write-Host "Setup completed successfully!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Configure your email settings in common/config/main-local.php" -ForegroundColor White
Write-Host "2. Add your Google Books API key in common/config/params.php" -ForegroundColor White
Write-Host "3. Access your application:" -ForegroundColor White
Write-Host "   - Frontend: http://localhost/bookshop/frontend/web/" -ForegroundColor Gray
Write-Host "   - Backend: http://localhost/bookshop/backend/web/" -ForegroundColor Gray
Write-Host ""
Write-Host "For detailed configuration instructions, see README.md" -ForegroundColor Cyan
Write-Host ""
Read-Host "Press Enter to exit"
