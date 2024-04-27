<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // seed the roles 
        $this->call([RoleSeeder::class]);

        // seed the users
        $super_admin =  User::create([
            'name' => 'Maher',
            'email' => 'maherr10203@gmail.com',
            'password' => Hash::make('maher123'),
            'email_verified_at'=> now()
        ]);
        $super_admin->assignRole('SuperAdmin');

        $admin = User::create([
            'name' => 'khalil',
            'email' => 'ahmed@khalil.com',
            'password' => Hash::make('ahmed123'),
            'email_verified_at'=> now()
        ]);
        $admin->assignRole('Admin');

        $employee = User::create([
            'name' => 'hamza',
            'email' => 'hamza@hamza.com',
            'password' => Hash::make('hamza123'),
            'email_verified_at'=> now()
        ]);
        $employee->assignRole('Employee');

        $client = User::create([
            'name' => 'nail',
            'email' => 'nail@nail.com',
            'password' => Hash::make('nail1234'),
            'email_verified_at'=> now()
        ]);
        $client->assignRole('Client');
    }
}
