<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $guarded = [];


    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan', 'id_satuan');
    }

    public function persediaan()
    {
        return $this->hasOne(Persediaan::class, 'id_barang');
    }

    public function masuk()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang');
    }

    public function keluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_barang');
    }
    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'id_jenis');
    }
    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'id_kategori');
    }
}
