<?php

namespace App\Http\Controllers\Pimpinan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('views.pimpinan.notifikasi');
    }
}
