<?php

namespace Database\Seeders;

use App\Enums\Role as RoleEnum;
use App\Models\Role as RoleModel;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (RoleEnum::cases() as $role) {
            RoleModel::query()->updateOrCreate(
                ['slug' => $role->value],
                [
                    'name' => $role->value,
                    'permissions' => [],
                ]
            );
        }
    }
}
