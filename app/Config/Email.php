<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = '';
    public string $fromName   = '';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter 4.6';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = '';

    /**
     * SMTP Username
     */
    public string $SMTPUser = '';

    /**
     * SMTP Password
     */
    public string $SMTPPass = '';

    /**
     * SMTP Port
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = '';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;

    /**
     * Constructor to load email settings from environment variables
     */
    public function __construct()
    {
        parent::__construct();
        
        // Load email settings from environment variables
        $this->fromEmail = env('MAIL_FROM_EMAIL', 'support@fakhrcs.com');
        $this->fromName = env('MAIL_FROM_NAME', 'FakhrCS');
        $this->SMTPHost = env('MAIL_HOST', 'localhost');
        $this->SMTPPort = (int) env('MAIL_PORT', 587);
        $this->SMTPUser = env('MAIL_USERNAME', env('MAIL_USER', ''));
        $this->SMTPPass = env('MAIL_PASSWORD', env('MAIL_PASS', ''));

        $smtpCrypto = strtolower(trim((string) env('MAIL_CRYPTO', '')));
        if (! in_array($smtpCrypto, ['', 'ssl', 'tls'], true)) {
            $smtpCrypto = '';
        }

        $this->SMTPCrypto = $smtpCrypto;
        
        // Set protocol based on environment
        if (env('MAIL_DRIVER') === 'mailtrap' || env('MAIL_DRIVER') === 'smtp') {
            $this->protocol = 'smtp';
            
            // Mailtrap specific configuration
            if (env('MAIL_DRIVER') === 'mailtrap') {
                // For port 587, disable encryption as Mailtrap supports plain auth
                if ($this->SMTPPort == 587) {
                    $this->SMTPCrypto = ''; // No encryption for port 587 plain auth
                } elseif ($this->SMTPPort == 2525) {
                    $this->SMTPCrypto = 'tls'; // Use STARTTLS for port 2525
                } elseif ($this->SMTPPort == 465) {
                    $this->SMTPCrypto = 'ssl'; // Use SSL for port 465
                } else {
                    $this->SMTPCrypto = ''; // Default to no encryption
                }
                
                // Increase timeout for Mailtrap
                $this->SMTPTimeout = 30;
                
                // Enable debugging for development
                if (ENVIRONMENT === 'development') {
                    $this->validate = true;
                }
            } elseif ($this->SMTPCrypto === '') {
                if ($this->SMTPPort === 465) {
                    $this->SMTPCrypto = 'ssl';
                } elseif ($this->SMTPPort === 587 || $this->SMTPPort === 2525) {
                    $this->SMTPCrypto = 'tls';
                }
            }
        }
    }
}
