<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class IdPermintaan extends Model
{
    protected $table = 'id_permintaan';
    protected $guarded = [];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_barang_id');
    }
}
