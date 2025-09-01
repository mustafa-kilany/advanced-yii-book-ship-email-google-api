<?php
return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'supportEmail' => 'noreply@example.com', // Required for signup emails
    'user.passwordResetTokenExpire' => 3600,
    'user.emailVerificationTokenExpire' => 43200, // 12 hours
    'user.passwordMinLength' => 6, // Minimum password length for signup
    
    // Bookshop specific parameters
    'googleBooksApiKey' => 'YOUR_GOOGLE_BOOKS_API_KEY', // Google Books API Key
    'previewPageLimit' => 10, // Number of pages users can read for free
    'wordsPerPage' => 250, // Average words per page for content calculation
];
