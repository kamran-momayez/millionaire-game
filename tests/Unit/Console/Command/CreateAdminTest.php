<?php

namespace Tests\Unit\Console\Command;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateAdminTest extends TestCase
{
    use DatabaseMigrations;

    public function test_should_fail_when_password_is_invalid()
    {
        $this->artisan('admin:create', [
            'name' => 'John',
            'surname' => 'Doe',
            'password' => 'pass'
        ])->assertExitCode(1)
            ->expectsOutput('Password must be a minimum of 6 characters.');

        $this->assertDatabaseMissing('users', [
            'name' => 'John',
            'surname' => 'Doe',
            'role' => 'ROLE_ADMIN',
        ]);
    }

    public function test_should_create_admin_when_there_is_no_error()
    {
        $this->artisan('admin:create', [
            'name' => 'John',
            'surname' => 'Doe',
            'password' => 'password'
        ])->assertExitCode(0)
            ->expectsOutput('New admin is created!');

        $this->assertDatabaseHas('users', [
            'name' => 'John',
            'surname' => 'Doe',
            'role' => 'ROLE_ADMIN',
        ]);
    }
}
