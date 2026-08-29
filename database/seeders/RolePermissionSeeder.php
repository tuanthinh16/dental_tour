<?php
namespace Database\Seeders;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            "dashboard.view",
            "destinations.view",
            "destinations.create",
            "destinations.update",
            "destinations.delete",
            "tours.view",
            "tours.create",
            "tours.update",
            "tours.delete",
            "consultations.view",
            "consultations.update",
            "pages.view",
            "pages.create",
            "pages.update",
            "pages.delete",
            "settings.view",
            "settings.update",
            "admins.manage",
        ];
        foreach ($codes as $code) {
            Permission::updateOrCreate(
                ["code" => $code],
                ["name" => $code, "is_active" => true],
            );
        }
        foreach (
            [
                "super_admin" => "Super Admin",
                "admin" => "Admin",
                "editor" => "Editor",
            ]
            as $code => $name
        ) {
            Role::updateOrCreate(
                ["code" => $code],
                ["name" => $name, "is_active" => true],
            );
        }
        $all = Permission::pluck("id");
        Role::where("code", "super_admin")
            ->firstOrFail()
            ->permissions()
            ->sync($all);
        Role::where("code", "admin")
            ->firstOrFail()
            ->permissions()
            ->sync(
                Permission::where("code", "!=", "admins.manage")->pluck("id"),
            );
        Role::where("code", "editor")
            ->firstOrFail()
            ->permissions()
            ->sync(
                Permission::whereIn("code", [
                    "dashboard.view",
                    "destinations.view",
                    "destinations.update",
                    "tours.view",
                    "tours.update",
                    "consultations.view",
                    "pages.view",
                    "pages.update",
                ])->pluck("id"),
            );
    }
}
