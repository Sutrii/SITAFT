<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalDosenController extends Controller
{
    public function index()
    {
        return view('jadwal-dosen.index');
    }
}
