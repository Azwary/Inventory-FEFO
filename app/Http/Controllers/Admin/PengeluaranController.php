<?php

namespace App\Http\Controllers\Admin;

use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PengeluaranController extends Controller
{
    public function index()
    {
        $stoks = StokBarang::with(['obat', 'jenis', 'kategori', 'satuan'])->get();
        return view('views.admin.pengeluaran', compact('stoks'));
    }
}
