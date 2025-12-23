<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@tots.com',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@tots.com',
            'is_admin' => false,
        ]);

        User::factory(5)->create();

        $this->call([
            SpaceSeeder::class,
        ]);
    }
}
