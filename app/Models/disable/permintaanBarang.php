<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanBarang extends Model
{
    protected $table = 'permintaan_barang';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function idPermintaan()
    {
        return $this->hasOne(IdPermintaan::class, 'permintaan_barang_id');
    }
}
