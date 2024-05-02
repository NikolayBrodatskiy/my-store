<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Orchid\Platform\Dashboard;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(2)->create();

        // User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@admin.com',
        //     'password' => Hash::make('password'),
        //     'role' => 1,
        //     'permissions' => json_encode(["platform.index" => true, "platform.systems.roles" => true, "platform.systems.users" => true, "platform.systems.attachment" => true]),

        // ]);
        User::factory()->create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
            'role' => 1,
        ]);
        User::factory()->create([
            'name' => 'user2',
            'email' => 'user2@user.com',
            'password' => Hash::make('password'),
            'role' => 1,
        ]);
    }
}
