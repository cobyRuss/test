<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (AdminUser::query()->count() === 0) {
            AdminUser::query()->create([
                'username' => 'admin',
                'password_hash' => bcrypt('admin123'),
            ]);
        }
    }
}
