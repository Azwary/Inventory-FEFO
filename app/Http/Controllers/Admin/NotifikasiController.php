<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('views.admin.notifikasi');
    }
}
