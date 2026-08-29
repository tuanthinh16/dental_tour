<?php
namespace Database\Seeders;
use App\Models\AdminUser;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/** Seeder bootstrap một lần; có thể xóa file sau khi tài khoản admin đầu tiên được tạo. */
class InitialAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (AdminUser::where("email", "admin@example.com")->exists()) {
            return;
        }
        $admin = AdminUser::create([
            "name" => "admin",
            "email" => "admin@example.com",
            "password" => Hash::make("123"),
            "is_active" => true,
        ]);
        $admin
            ->roles()
            ->attach(Role::where("code", "super_admin")->firstOrFail());
    }
}
