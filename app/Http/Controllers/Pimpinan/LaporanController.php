<?php

namespace App\Http\Controllers\Pimpinan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        return view('views.pimpinan.laporan');
    }
}
