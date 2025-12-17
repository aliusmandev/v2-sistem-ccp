<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data admin untuk RSAB-SDR (RS Awal Bros Sudirman)
        $company = [
            'Kode' => 'RSAB-SDR',
            'Nama' => 'RSAB Sudirman',
        ];

        $role = Role::firstOrCreate(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);

        $email = strtolower(str_replace([' ', '-'], ['', ''], $company['Kode'])) . '@admin.com';
        $user = User::firstOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => 'Administrator ' . $company['Nama'],
                'password' => bcrypt('123456'),
                'kodeperusahaan' => $company['Kode'],
            ]
        );
        $user->assignRole([$role->id]);
    }
}
