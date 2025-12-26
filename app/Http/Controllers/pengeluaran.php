<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pengeluaran extends Controller
{
    public function index()
    {
        return view('admin.pengeluaran');
    }
}
