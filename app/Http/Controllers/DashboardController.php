<?php

namespace App\Http\Controllers;

use App\Models\JadwalDosen;
use App\Models\Jadwal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index($month = null)
    {
        $month = $month ?? Carbon::now()->month;
        $today = Carbon::now()->locale('id')->isoFormat('dddd');
        $todayLower = strtolower($today);
        $currentMonth = Carbon::createFromDate(null, $month)->translatedFormat('F Y');

        $dosenKosong = JadwalDosen::with('dosen')
            ->whereRaw('LOWER(TRIM(hari)) = LOWER(TRIM(?))', [$today])
            ->where('status', 'Kosong')
            ->get()
            ->groupBy('userId');

        $jadwalSeminar = Jadwal::with(['mahasiswa', 'skripsi'])
            ->whereMonth('jadwal_seminar', $month)
            ->orderBy('jadwal_seminar', 'asc')
            ->get();

        $eventDays = $jadwalSeminar->mapWithKeys(fn($j) => [
            Carbon::parse($j->jadwal_seminar)->day => $j->status
        ]);

        return view('dashboard.index', compact('dosenKosong', 'jadwalSeminar', 'eventDays', 'currentMonth'));
    }

    public function fetchMonth($month)
    {
        $jadwalSeminar = Jadwal::with(['mahasiswa', 'skripsi'])
            ->whereMonth('jadwal_seminar', $month)
            ->orderBy('jadwal_seminar', 'asc')
            ->get();

        return response()->json($jadwalSeminar);
    }

    public function fetchData($month, $day = null)
    {
        $day = $day ?? Carbon::now()->locale('id')->isoFormat('dddd');
        $dayLower = strtolower($day);

        $dosenKosong = JadwalDosen::with('dosen')
            ->whereRaw('LOWER(TRIM(hari)) = LOWER(TRIM(?))', [$today])
            ->where('status', 'Kosong')
            ->get()
            ->groupBy('userId');

        $jadwalSeminar = Jadwal::with(['mahasiswa', 'skripsi'])
            ->whereMonth('jadwal_seminar', $month)
            ->orderBy('jadwal_seminar', 'asc')
            ->get();

        return response()->json([
            'dosenKosong' => $dosenKosong,
            'jadwalSeminar' => $jadwalSeminar,
        ]);
    }
}
