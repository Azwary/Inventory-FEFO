<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    protected $table = 'stok_barang';
    protected $primaryKey = 'id_stok';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
