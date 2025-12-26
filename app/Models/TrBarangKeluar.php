<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrBarangKeluar extends Model
{
    protected $table = 'tr_barang_keluar';
    protected $guarded = [];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }
}
