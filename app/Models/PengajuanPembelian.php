<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanPembelian extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengajuan_pembelians';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function getPerusahaan()
    {
        return $this->hasOne(MasterPerusahaan::class, 'Kode', 'KodePerusahaan');
    }

    public function getJenisPermintaan()
    {
        return $this->hasOne(MasterJenisPengajuan::class, 'id', 'Jenis');
    }


    /**
     * Get all of the comments for the PengajuanPembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getVendor()
    {
        return $this->hasMany(ListVendor::class, 'IdPengajuan', 'id');
    }

    public function getVendorDetail()
    {
        return $this->hasMany(ListVendorDetail::class, 'IdPengajuan', 'id');
    }

    public function getPengajuanItem()
    {
        return $this->hasMany(PengajuanItem::class, 'IdPengajuan', 'id');
    }

}
