<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Notifikasi extends Controller
{
    public function index()
    {
        return view('admin.Notifikasi');
    }
}
