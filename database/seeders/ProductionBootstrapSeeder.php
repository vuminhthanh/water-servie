<?php

namespace Database\Seeders;

use Filament\Models\User as FilamentUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class ProductionBootstrapSeeder extends Seeder
{
    public function run()
    {
        $this->call(ProductCatalogSeeder::class);

        $email = trim((string) env('ADMIN_EMAIL'));
        $password = (string) env('ADMIN_PASSWORD');

        if ($email === '' && $password === '') {
            $this->command->warn('Bỏ qua tạo admin vì chưa cấu hình ADMIN_EMAIL và ADMIN_PASSWORD.');
            return;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('ADMIN_EMAIL không hợp lệ.');
        }

        if (strlen($password) < 8) {
            throw new RuntimeException('ADMIN_PASSWORD phải có ít nhất 8 ký tự.');
        }

        FilamentUser::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Quản trị viên'),
                'password' => Hash::make($password),
                'is_admin' => true,
                'roles' => [],
            ]
        );
    }
}
