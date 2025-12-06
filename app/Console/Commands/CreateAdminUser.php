<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateAdminUser extends Command
{
    protected $signature = 'make:admin';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $this->info('Creating Admin User...');

        $name = $this->ask('Name', 'Admin');
        $email = $this->ask('Email', 'admin@admin.com');
        $password = $this->secret('Password');
        
        if (!$password) {
            $this->error('Password cannot be empty!');
            return;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        // Ensure role exists
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        
        $user->assignRole($role);

        $this->info("User {$user->name} ({$user->email}) created successfully with Super Admin role!");
    }
}
