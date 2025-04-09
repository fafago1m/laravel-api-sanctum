<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $developerRole = Role::firstOrCreate(['name' => 'developer']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        
        $developer = User::firstOrCreate([
            'email' => 'dev@gmail.com',
        ], [
            'name' => 'Developer',
            'password' => Hash::make('password'),
        ]);
        $developer->assignRole($developerRole);

        
        $user = User::firstOrCreate([
            'email' => 'user@gmail.com',
        ], [
            'name' => 'User',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($userRole);
    }
}
