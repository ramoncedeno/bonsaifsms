<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'r.cedeno@uinenlinea.mx'],
            ['name' => 'Ramón Cedeño',
             'password'=>'Rc465798'
            ]
        );

        $this->call(RolesAndPermissionsSeeder::class);
    }
}
