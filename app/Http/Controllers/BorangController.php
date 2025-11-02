<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BorangController extends Controller
{
    /**
     * Tampilkan halaman Borang Penilaian.
     */
    public function index()
    {
        return view('borang.index');
    }
}
