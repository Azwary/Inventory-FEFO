<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrBarangMasuk extends Model
{
    protected $table = 'tr_barang_masuk';
    protected $primaryKey = 'id_tr_masuk';
    protected $guarded = [];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id');
    }
}
