<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'servidor@pref.gov.br'],
            [
                'name'               => 'Operador Administrativo',
                'password'           => Hash::make('password'),
                'role'               => \App\Enums\UserRole::ADMIN,
                'is_active'          => true,
                'email_verified_at'  => now(),
            ]
        );
    }
}
