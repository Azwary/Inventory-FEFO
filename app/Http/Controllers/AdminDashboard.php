<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboard extends Controller
{
    public function index()
    {
        return view('admin.layouts.app');
    }
    public function dashboard()
    {

        return view('admin.dashboard');
    }
}
