<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';
    protected $primaryKey = 'id_keluar';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasOne(TrBarangKeluar::class, 'barang_keluar_id');
    }


    public function trKeluar()
    {
        return $this->hasOne(TrBarangKeluar::class, 'id_keluar', 'id_keluar');
    }
}
