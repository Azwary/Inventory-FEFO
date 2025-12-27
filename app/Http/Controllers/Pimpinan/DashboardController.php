<?php

namespace App\Http\Controllers\Pimpinan;

use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('views.layouts.app');
    }
    public function dashboard()
    {

        return view('views.pimpinan.dashboard');
    }
}
