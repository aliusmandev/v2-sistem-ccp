<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanInvestasi extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usulan_investasis';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get all of the comments for the UsulanInvestasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getFuiDetail()
    {
        return $this->hasMany(UsulanInvestasiDetail::class, 'IdUsulan', 'id');
    }
    public function getBarang()
    {
        return $this->hasOne(MasterBarang::class, 'id', 'IdBarang');
    }
    public function getVendor()
    {
        return $this->hasOne(MasterVendor::class, 'id', 'IdVendor');
    }
}
