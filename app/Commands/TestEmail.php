<?php

/**
 * Simple Email Test using CodeIgniter Spark Command
 * Run this with: php spark test:email
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestEmail extends BaseCommand
{
    protected $group       = 'test';
    protected $name        = 'test:email';
    protected $description = 'Test email configuration and sending';

    public function run(array $params)
    {
        CLI::write('Testing CodeIgniter Email Service...', 'yellow');
        CLI::write('Environment: ' . ENVIRONMENT);
        
        try {
            // Get email service
            $email = \Config\Services::email();
            CLI::write('Email service loaded successfully', 'green');
            
            // Get email configuration
            $config = config('Email');
            CLI::write('Email Configuration:');
            CLI::write('- Protocol: ' . $config->protocol);
            CLI::write('- SMTP Host: ' . $config->SMTPHost);
            CLI::write('- SMTP Port: ' . $config->SMTPPort);
            CLI::write('- SMTP User: ' . $config->SMTPUser);
            CLI::write('- SMTP Crypto: ' . $config->SMTPCrypto);
            CLI::write('- From Email: ' . $config->fromEmail);
            CLI::write('- From Name: ' . $config->fromName);
            
            // Test email settings
            $email->setFrom($config->fromEmail, $config->fromName);
            $email->setTo('test@example.com');
            $email->setSubject('Test Email from MSARLink');
            $email->setMessage('This is a test email to verify CodeIgniter email configuration.');
            
            CLI::write('Attempting to send test email...', 'yellow');
            
            // Enable debug mode
            $email->setMailType('html');
            
            // Try to send email
            if ($email->send()) {
                CLI::write('SUCCESS: Email sent successfully!', 'green');
                CLI::write('Debug Info:');
                CLI::write($email->printDebugger(['headers', 'subject', 'body']));
            } else {
                CLI::write('ERROR: Failed to send email', 'red');
                CLI::write('Debug Info:');
                CLI::write($email->printDebugger(['headers', 'subject', 'body']));
                
                // Get detailed error information
                $errors = $email->printDebugger();
                CLI::write('Detailed Errors:');
                CLI::write($errors);
            }
            
        } catch (\Exception $e) {
            CLI::write('EXCEPTION: ' . $e->getMessage(), 'red');
            CLI::write('File: ' . $e->getFile());
            CLI::write('Line: ' . $e->getLine());
            CLI::write('Trace:');
            CLI::write($e->getTraceAsString());
        }
        
        CLI::write('Test completed.', 'yellow');
    }
}
?>