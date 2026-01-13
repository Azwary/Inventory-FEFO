<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrBarangKeluar extends Model
{
    protected $table = 'tr_barang_keluar';
    protected $primaryKey = 'id_tr_keluar';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }
}
