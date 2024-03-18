<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name} {surname} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $surname = $this->argument('surname');
        $password = $this->argument('password');

        if (strlen($password) < 6) {
            $this->error('Password must be a minimum of 6 characters.');
            return CommandAlias::FAILURE;
        }

        User::create([
            'name' => $name,
            'surname' => $surname,
            'password' => Hash::make($password),
            'role' => 'ROLE_ADMIN',
        ]);

        $this->info('New admin is created!');
        return CommandAlias::SUCCESS;
    }
}
