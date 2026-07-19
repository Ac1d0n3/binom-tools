<?php

namespace App\Console\Commands;

use App\Accounts\AccountsConfig;
use App\Accounts\UserRepository;
use Illuminate\Console\Command;

class SetAccountUserPasswordCommand extends Command
{
    protected $signature = 'bn-tools:user-password
                            {email : User email}
                            {--password= : New password (prompted if omitted)}';

    protected $description = 'Set a hashed password for a bn-tools file-based account user (never stores plaintext)';

    public function handle(AccountsConfig $config, UserRepository $users): int
    {
        if (! $config->enabled()) {
            $this->warn('Accounts are disabled (BINOM_TOOLS_ACCOUNTS_ENABLED=false). Password will still be written to users.json.');
        }

        $email = (string) $this->argument('email');
        $password = $this->option('password');
        if (! is_string($password) || $password === '') {
            $password = $this->secret('New password');
        }
        if (! is_string($password) || strlen($password) < 8) {
            $this->error('Password must be at least 8 characters.');

            return self::FAILURE;
        }

        $user = $users->findByEmail($email);
        if ($user === null) {
            $this->error('User not found. Create the user in users.json or via the account UI first.');

            return self::FAILURE;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $users->setPasswordHash($email, $hash);
        $this->info("Password hash updated for {$email}.");

        return self::SUCCESS;
    }
}
