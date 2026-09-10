<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }
        foreach (['admin' => 'Administrador', 'professor' => 'Professor'] as $role => $nome) {
            $user = User::firstOrNew(['email' => $role.'@escola.test']);
            $user->name = $nome;
            $user->password = Hash::make('senha123');
            $user->role = $role;
            $user->save();
        }
    }
}
