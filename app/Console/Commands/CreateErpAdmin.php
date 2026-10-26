<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateErpAdmin extends Command
{
    protected $signature = 'erp:create-admin';
    protected $description = 'Provision the initial ERP super administrator using a private terminal.';

    public function handle(): int
    {
        if (User::where('role', 'super_admin')->exists()) {
            $this->error('Super Admin already exists. Use a reviewed admin-management flow instead.');
            return self::FAILURE;
        }
        $name = $this->ask('Administrator name');
        $email = $this->ask('Administrator email');
        $password = $this->secret('New password (minimum 12 characters)');
        $confirm = $this->secret('Repeat password');
        $validation = Validator::make(compact('name', 'email', 'password'), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);
        if ($validation->fails() || $password !== $confirm) {
            $this->error('Invalid input or passwords differ: '.$validation->errors()->first());
            return self::FAILURE;
        }
        User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password), 'role' => 'super_admin']);
        $this->info('Super Admin created. There is no public signup route.');
        return self::SUCCESS;
    }
}
