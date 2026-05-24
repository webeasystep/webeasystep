<?php

namespace App\Commands;

use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Authentication\Actions\EmailActivator;
use CodeIgniter\Shield\Entities\User;
use Config\Services;

/**
 * Resends activation emails to inactive users and recreates missing activation codes.
 */
class ResendActivationEmails extends BaseCommand
{
    protected $group       = 'Auth';
    protected $name        = 'auth:resend-activations';
    protected $description = 'Resend activation emails to inactive users with email identities.';
    protected $usage       = 'auth:resend-activations [options]';
    protected $options     = [
        '--dry-run' => 'Preview which users will be targeted without sending emails.',
        '--email'   => 'Target a single email address only.',
        '--limit'   => 'Maximum number of users to process in one run.',
    ];

    /**
     * Runs the bulk resend process with safe filters and summary output.
     */
    public function run(array $params)
    {
        $dryRun      = (bool) CLI::getOption('dry-run');
        $targetEmail = trim($this->readOptionValue('email'));
        $limit       = (int) $this->readOptionValue('limit');

        CLI::write('Activation Email Recovery Tool', 'yellow');
        CLI::write('Time: ' . date('Y-m-d H:i:s'));

        if ($dryRun) {
            CLI::write('DRY RUN MODE - no emails will be sent.', 'cyan');
        }

        $targets = $this->getTargetUsers($targetEmail, $limit);
        $count   = count($targets);

        CLI::write("Inactive users eligible for resend: {$count}", 'green');

        if ($count === 0) {
            CLI::write('No inactive users found for processing.', 'yellow');

            return EXIT_SUCCESS;
        }

        $sentCount  = 0;
        $errorCount = 0;

        foreach ($targets as $row) {
            $label = sprintf('[User #%d] %s <%s>', $row->id, $row->full_name ?: 'User', $row->email);

            if ($dryRun) {
                CLI::write($label . ($row->activation_code ? ' - existing activation code found' : ' - activation code will be created'), 'white');
                continue;
            }

            try {
                $user = $this->getUserModel()->find($row->id);
                if (! $user instanceof User) {
                    CLI::write($label . ' - user entity not found', 'red');
                    $errorCount++;
                    continue;
                }

                $activationCode = $this->regenerateActivationIdentity($user);
                $this->sendActivationEmail($user, $row->email, $activationCode);

                CLI::write($label . ' - activation email sent', 'green');
                $sentCount++;
            } catch (\Throwable $e) {
                log_message('error', 'ResendActivationEmails failed for user_id {id}: {message}', [
                    'id'      => $row->id,
                    'message' => $e->getMessage(),
                ]);

                CLI::write($label . ' - failed: ' . $e->getMessage(), 'red');
                $errorCount++;
            }
        }

        CLI::newLine();
        CLI::write("Completed. Sent: {$sentCount}, Failed: {$errorCount}", $errorCount === 0 ? 'green' : 'yellow');

        return $errorCount === 0 ? EXIT_SUCCESS : EXIT_ERROR;
    }

    /**
     * Returns inactive users that have an email identity and may need a new activation email.
     */
    private function getTargetUsers(string $targetEmail, int $limit): array
    {
        $builder = db_connect()->table('users')
            ->select('users.id, users.full_name, users.active, email_identity.secret AS email, activation_identity.secret AS activation_code')
            ->join('auth_identities AS email_identity', 'email_identity.user_id = users.id AND email_identity.type = "email_password"', 'inner')
            ->join('auth_identities AS activation_identity', 'activation_identity.user_id = users.id AND activation_identity.type = "email_activate"', 'left')
            ->where('users.active', 0)
            ->orderBy('users.id', 'DESC');

        if ($targetEmail !== '') {
            $builder->where('email_identity.secret', $targetEmail);
        }

        if ($limit > 0) {
            $builder->limit($limit);
        }

        return $builder->get()->getResult();
    }

    /**
     * Deletes any stale activation identity and creates a fresh code.
     */
    private function regenerateActivationIdentity(User $user): string
    {
        $activator = new EmailActivator();

        return $activator->createIdentity($user);
    }

    /**
     * Sends the activation email using the current project template and SMTP settings.
     */
    private function sendActivationEmail(User $user, string $recipientEmail, string $activationCode): void
    {
        $activationUrl = site_url('auth/a/activate') . '?token=' . $activationCode;
        $message       = Services::renderer()
            ->setData([
                'code'           => $activationCode,
                'ipAddress'      => 'CLI',
                'userAgent'      => 'Spark Command',
                'date'           => Time::now()->toDateTimeString(),
                'user'           => $user,
                'full_name'      => $user->full_name,
                'activation_url' => $activationUrl,
            ], 'raw')
            ->render(setting('Auth.views')['action_email_activate_email'], ['debug' => false]);

        $email = Services::email();
        $email->setMailType('html');
        $email->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? '');

        $email->setTo($recipientEmail);
        $email->setSubject(lang('Auth.emailActivateSubject'));
        $email->setMessage($message);

        if ($email->send(false) === false) {
            $debugger = $email->printDebugger(['headers', 'subject']);
            $email->clear();

            throw new \RuntimeException('SMTP send failed. ' . trim($debugger));
        }

        $email->clear();
    }

    /**
     * Returns the shared user model instance.
     */
    private function getUserModel(): UserModel
    {
        return model(UserModel::class);
    }

    /**
     * Reads CLI options reliably for both `--name=value` and `--name value` formats.
     */
    private function readOptionValue(string $name): string
    {
        $option = CLI::getOption($name);
        if (is_string($option) && $option !== '') {
            return $option;
        }

        $argv = $_SERVER['argv'] ?? [];
        $argc = count($argv);

        for ($i = 0; $i < $argc; $i++) {
            $argument = (string) $argv[$i];

            if (strpos($argument, '--' . $name . '=') === 0) {
                return substr($argument, strlen($name) + 3);
            }

            if ($argument === '--' . $name && isset($argv[$i + 1]) && strpos((string) $argv[$i + 1], '--') !== 0) {
                return (string) $argv[$i + 1];
            }
        }

        return '';
    }
}
