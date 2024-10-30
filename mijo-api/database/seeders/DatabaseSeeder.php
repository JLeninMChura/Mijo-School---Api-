<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Course\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Luis Martin',
            'surname' => 'Vilca Hilasaca',
            'email' => 'luis@gmail.com',
            'password' => bcrypt('12345678'),
            'state' => '1',
            'type_user' => '2',
        ]);
        \App\Models\Role::factory()->create([
            'name' => 'Administrador',
        ]);
        Category::factory()->create([
            'name' => 'Importación',
            'state' => '1',
        ]);
    }
}
