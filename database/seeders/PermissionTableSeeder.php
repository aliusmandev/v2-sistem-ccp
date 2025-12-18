<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'data-master',
            'kelola-pengguna',
            // Perusahaan
            'perusahaan-list',
            'perusahaan-create',
            'perusahaan-edit',
            'perusahaan-delete',
            // Departemen
            'departemen-list',
            'departemen-create',
            'departemen-edit',
            'departemen-delete',
            // Jabatan
            'jabatan-list',
            'jabatan-create',
            'jabatan-edit',
            'jabatan-delete',
            // Satuan Barang
            'satuan-barang-list',
            'satuan-barang-create',
            'satuan-barang-edit',
            'satuan-barang-delete',
            // Merek
            'master-merk-list',
            'master-merk-create',
            'master-merk-edit',
            'master-merk-delete',
            // Barang
            'barang-list',
            'barang-create',
            'barang-edit',
            'barang-delete',
            // Vendor
            'vendor-list',
            'vendor-create',
            'vendor-edit',
            'vendor-delete',
            // Parameter
            'parameter-list',
            'parameter-create',
            'parameter-edit',
            'parameter-delete',
            // Master Form
            'nama-form-list',
            'nama-form-create',
            'nama-form-edit',
            'nama-form-delete',
            // Master Jenis Pengajuan
            'jenis-pengajuan-list',
            'jenis-pengajuan-create',
            'jenis-pengajuan-edit',
            'jenis-pengajuan-delete',
            // Role/User/Permission (opsional tetap dimasukkan)
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            // Approvals & Others - jika tetap diperlukan
            'pengajuan-pembelian-list',
            'pengajuan-pembelian-create',
            'pengajuan-pembelian-edit',
            'pengajuan-pembelian-delete',
            // Permintaan
            'permintaan-list',
            'permintaan-create',
            'permintaan-edit',
            'permintaan-delete',
            // Permintaan,
            //
            'permintaan-approve-kepala-divisi',
            'permintaan-approve-penunjang',
            'permintaan-approve-direktur',
            'permintaan-approve-logistik',
            // hta
            'hta-gpa-approve-penilai1',
            'hta-gpa-approve-penilai2',
            'hta-gpa-approve-penilai3',
            'hta-gpa-approve-penilai4',
            'hta-gpa-approve-penilai5',
            // fui
            'fui-approve-jangmed',
            'fui-approve-direktur',
            // hta-gpa
            'hta-gpa-create',
            'hta-gpa-edit',
            'hta-gpa-delete',
            'hta-gpa-show',
            // fui
            'fui-create',
            'fui-edit',
            'fui-delete',
            'fui-show',
            'fui-print',
            // rekomendasi
            'rekomendasi-list',
            'rekomendasi-create',
            'rekomendasi-edit',
            'rekomendasi-delete',
            'rekomendasi-show',
            'rekomendasi-print',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
