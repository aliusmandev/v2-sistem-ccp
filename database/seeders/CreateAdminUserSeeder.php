<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // List of companies as in MasterPerusahaanSeeder
        $companies = [
            [
                'Kode' => 'ABG001',
                'Nama' => 'RS Awal Bros Sudirman',
            ],
            [
                'Kode' => 'ABG002',
                'Nama' => 'RS Awal Bros Panam',
            ],
            [
                'Kode' => 'ABG003',
                'Nama' => 'RS Awal Bros Ahmad Yani',
            ],
            [
                'Kode' => 'ABG004',
                'Nama' => 'RS Awal Bros Hangtuah',
            ],
            [
                'Kode' => 'ABG005',
                'Nama' => 'RS Awal Bros Dumai',
            ],
            [
                'Kode' => 'ABG006',
                'Nama' => 'RS Awal Bros Ujung Batu',
            ],
            [
                'Kode' => 'ABG007',
                'Nama' => 'RS Awal Bros Bagan Batu',
            ],
            [
                'Kode' => 'ABG008',
                'Nama' => 'RS Awal Bros Batam',
            ],
            [
                'Kode' => 'ABG009',
                'Nama' => 'RS Awal Bros Botania',
            ],
            [
                'Kode' => 'CSC001',
                'Nama' => 'PT Langit Biru Sehat Sentosa',
            ],
            [
                'Kode' => 'CSC002',
                'Nama' => 'PT Cahaya Perdana Nusantara',
            ],
            [
                'Kode' => 'CSC003',
                'Nama' => 'PT Digital Indonesia Hebat',
            ],
            [
                'Kode' => 'CSC004',
                'Nama' => 'PT Digital Kalibrasi Hebat',
            ],
            [
                'Kode' => 'CSC005',
                'Nama' => 'Awal Bros Training Center',
            ],
        ];

        $role = Role::firstOrCreate(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);

        foreach ($companies as $company) {
            $email = strtolower(str_replace(' ', '', $company['Kode'])) . '@admin.com';
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
}
