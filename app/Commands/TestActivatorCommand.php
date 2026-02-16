<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestActivatorCommand extends BaseCommand
{
    protected $group       = 'test';
    protected $name        = 'test:activator';
    protected $description = 'Test the EmailActivator service';

    public function run(array $params)
    {
        CLI::write('Testing EmailActivator service...', 'yellow');

        // Get a test user with email identity
        $userModel = model('App\Models\UserModel');
        $user = $userModel->find(3293); // User with email identity

        if (!$user) {
            CLI::error('User not found');
            return;
        }

        CLI::write("Testing with user ID: {$user->id}");
        CLI::write("User email from entity: " . ($user->email ?? 'NULL'));
        
        // Check if user has email identity
        $identityModel = model('CodeIgniter\Shield\Models\UserIdentityModel');
        $emailIdentity = $identityModel->getIdentityByType($user, 'email_password');
        if ($emailIdentity) {
            CLI::write("Email identity found: {$emailIdentity->secret}");
        } else {
            CLI::write("No email identity found");
        }

        try {
            $activator = new \App\Libraries\EmailActivator();
            $result = $activator->send($user);
            
            if ($result) {
                CLI::write('EmailActivator send result: SUCCESS', 'green');
                
                // Check if token was created
                $identityModel = model('CodeIgniter\Shield\Models\UserIdentityModel');
                $identity = $identityModel->getIdentityByType($user, 'email_activate');
                if ($identity) {
                    CLI::write("Token created: {$identity->secret}");
                } else {
                    CLI::write('No token found', 'red');
                }
            } else {
                CLI::write('EmailActivator send result: FAILED', 'red');
            }
        } catch (\Exception $e) {
            CLI::error("Error: {$e->getMessage()}");
            CLI::write("Stack trace: {$e->getTraceAsString()}");
        }
    }
}