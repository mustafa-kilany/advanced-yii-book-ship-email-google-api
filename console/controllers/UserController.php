<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use common\models\User;

/**
 * User management console controller
 */
class UserController extends Controller
{
    /**
     * Creates a new admin user
     */
    public function actionCreateAdmin()
    {
        $this->stdout("Create Admin User\n", Console::FG_GREEN);
        $this->stdout("================\n\n");
        
        // Get username
        $username = $this->prompt('Enter username:', ['required' => true]);
        
        // Check if user already exists
        if (User::findByUsername($username)) {
            $this->stderr("User with username '{$username}' already exists!\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        
        // Get email
        $email = $this->prompt('Enter email:', ['required' => true]);
        
        // Check if email already exists
        if (User::findOne(['email' => $email])) {
            $this->stderr("User with email '{$email}' already exists!\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        
        // Get password
        $password = $this->prompt('Enter password:', ['required' => true]);
        
        // Confirm password
        $confirmPassword = $this->prompt('Confirm password:', ['required' => true]);
        
        if ($password !== $confirmPassword) {
            $this->stderr("Passwords do not match!\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        
        // Get role
        $role = $this->prompt('Enter role (admin/author/reader):', [
            'default' => 'admin',
            'pattern' => '/^(admin|author|reader)$/',
            'validator' => function($input, &$error) {
                if (!in_array($input, ['admin', 'author', 'reader'])) {
                    $error = 'Role must be admin, author, or reader';
                    return false;
                }
                return true;
            }
        ]);
        
        // Create user
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->role = $role;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($password);
        $user->generateAuthKey();
        
        if ($user->save()) {
            $this->stdout("\nUser created successfully!\n", Console::FG_GREEN);
            $this->stdout("Username: {$username}\n");
            $this->stdout("Email: {$email}\n");
            $this->stdout("Role: {$role}\n");
            $this->stdout("Status: Active\n\n");
            
            return ExitCode::OK;
        } else {
            $this->stderr("Failed to create user:\n", Console::FG_RED);
            foreach ($user->errors as $field => $errors) {
                foreach ($errors as $error) {
                    $this->stderr("  {$field}: {$error}\n", Console::FG_RED);
                }
            }
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
    
    /**
     * Lists all users
     */
    public function actionList()
    {
        $users = User::find()->all();
        
        if (empty($users)) {
            $this->stdout("No users found.\n", Console::FG_YELLOW);
            return ExitCode::OK;
        }
        
        $this->stdout("User List\n", Console::FG_GREEN);
        $this->stdout("=========\n\n");
        
        $this->stdout(sprintf("%-20s %-30s %-15s %-10s %-10s\n", 
            'Username', 'Email', 'Role', 'Status', 'Created'));
        $this->stdout(str_repeat('-', 85) . "\n");
        
        foreach ($users as $user) {
            $status = $user->status == User::STATUS_ACTIVE ? 'Active' : 'Inactive';
            $created = date('Y-m-d', $user->created_at);
            
            $this->stdout(sprintf("%-20s %-30s %-15s %-10s %-10s\n",
                $user->username,
                $user->email,
                $user->role,
                $status,
                $created
            ));
        }
        
        $this->stdout("\nTotal users: " . count($users) . "\n");
        
        return ExitCode::OK;
    }
    
    /**
     * Changes user role
     */
    public function actionChangeRole($username)
    {
        $user = User::findByUsername($username);
        
        if (!$user) {
            $this->stderr("User '{$username}' not found!\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        
        $this->stdout("Change Role for User: {$username}\n", Console::FG_GREEN);
        $this->stdout("Current role: {$user->role}\n\n");
        
        $newRole = $this->prompt('Enter new role (admin/author/reader):', [
            'required' => true,
            'pattern' => '/^(admin|author|reader)$/',
            'validator' => function($input, &$error) {
                if (!in_array($input, ['admin', 'author', 'reader'])) {
                    $error = 'Role must be admin, author, or reader';
                    return false;
                }
                return true;
            }
        ]);
        
        $user->role = $newRole;
        
        if ($user->save()) {
            $this->stdout("Role changed successfully to '{$newRole}'\n", Console::FG_GREEN);
            return ExitCode::OK;
        } else {
            $this->stderr("Failed to change role!\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
    
    /**
     * Activates/deactivates a user
     */
    public function actionToggleStatus($username)
    {
        $user = User::findByUsername($username);
        
        if (!$user) {
            $this->stderr("User '{$username}' not found!\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
        
        $currentStatus = $user->status == User::STATUS_ACTIVE ? 'Active' : 'Inactive';
        $newStatus = $user->status == User::STATUS_ACTIVE ? User::STATUS_INACTIVE : User::STATUS_ACTIVE;
        $newStatusText = $newStatus == User::STATUS_ACTIVE ? 'Active' : 'Inactive';
        
        $this->stdout("Toggle Status for User: {$username}\n", Console::FG_GREEN);
        $this->stdout("Current status: {$currentStatus}\n");
        $this->stdout("New status: {$newStatusText}\n\n");
        
        $confirm = $this->prompt('Are you sure? (y/n):', [
            'required' => true,
            'pattern' => '/^(y|n)$/i'
        ]);
        
        if (strtolower($confirm) !== 'y') {
            $this->stdout("Operation cancelled.\n", Console::FG_YELLOW);
            return ExitCode::OK;
        }
        
        $user->status = $newStatus;
        
        if ($user->save()) {
            $this->stdout("Status changed successfully to '{$newStatusText}'\n", Console::FG_GREEN);
            return ExitCode::OK;
        } else {
            $this->stderr("Failed to change status!\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
    
    /**
     * Shows help information
     */
    public function actionHelp()
    {
        $this->stdout("User Management Console Commands\n", Console::FG_GREEN);
        $this->stdout("==============================\n\n");
        
        $this->stdout("Available commands:\n\n");
        
        $this->stdout("  create-admin     Create a new admin user\n", Console::FG_YELLOW);
        $this->stdout("  list            List all users\n", Console::FG_YELLOW);
        $this->stdout("  change-role     Change user role\n", Console::FG_YELLOW);
        $this->stdout("  toggle-status   Activate/deactivate user\n", Console::FG_YELLOW);
        $this->stdout("  help            Show this help\n", Console::FG_YELLOW);
        
        $this->stdout("\nExamples:\n\n");
        $this->stdout("  php yii user/create-admin\n", Console::FG_CYAN);
        $this->stdout("  php yii user/list\n", Console::FG_CYAN);
        $this->stdout("  php yii user/change-role username\n", Console::FG_CYAN);
        $this->stdout("  php yii user/toggle-status username\n", Console::FG_CYAN);
        
        return ExitCode::OK;
    }
}
