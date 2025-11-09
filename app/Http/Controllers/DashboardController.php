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
        $currentMonth = Carbon::createFromDate(null, $month)->translatedFormat('F Y');

        $dosenKosong = JadwalDosen::with('dosen')
            ->whereRaw('LOWER(TRIM(hari)) = LOWER(TRIM(?))', [$today])
            ->where('status', 'Kosong')
            ->get()
            ->groupBy('userId');

            $jadwalSeminar = Jadwal::with(['mahasiswa', 'skripsi'])
            ->whereMonth('jadwal_seminar', $month)
            ->whereDate('jadwal_seminar', '>=', Carbon::today())
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
            ->whereDate('jadwal_seminar', '>=', Carbon::today())
            ->orderBy('jadwal_seminar', 'asc')
            ->get();

        return response()->json($jadwalSeminar);
    }

    public function fetchData($month, $day = null)
    {
        $year = Carbon::now()->year;

        $selectedDate = Carbon::createFromDate($year, $month, $day ?? Carbon::now()->day);
        $hari = ucfirst($selectedDate->locale('id')->isoFormat('dddd'));

        $dosenKosong = JadwalDosen::with('dosen')
            ->whereRaw('LOWER(TRIM(hari)) = LOWER(TRIM(?))', [$hari])
            ->where('status', 'Kosong')
            ->get()
            ->groupBy('userId');

        $jadwalSeminar = Jadwal::with(['mahasiswa', 'skripsi'])
            ->whereMonth('jadwal_seminar', $month)
            ->whereDay('jadwal_seminar', $selectedDate->day)
            ->whereDate('jadwal_seminar', '>=', Carbon::today())
            ->orderBy('jadwal_seminar', 'asc')
            ->get();

        return response()->json([
            'hari' => $hari,
            'tanggal' => $selectedDate->isoFormat('D MMMM Y'),
            'dosenKosong' => $dosenKosong,
            'jadwalSeminar' => $jadwalSeminar,
        ]);
    }

    public function dataToday()
    {
        $today = Carbon::now();
        $hari = ucfirst($today->locale('id')->isoFormat('dddd'));

        $dosenKosong = JadwalDosen::with('dosen')
            ->whereRaw('LOWER(TRIM(hari)) = LOWER(TRIM(?))', [$hari])
            ->where('status', 'Kosong')
            ->get()
            ->groupBy('userId');

        $jadwalSeminar = Jadwal::with(['mahasiswa', 'skripsi'])
            ->whereDate('jadwal_seminar', $today)
            ->orderBy('jadwal_seminar', 'asc')
            ->get();

        return response()->json([
            'hari' => $hari,
            'tanggal' => $today->translatedFormat('d MMMM Y'),
            'dosenKosong' => $dosenKosong,
            'jadwalSeminar' => $jadwalSeminar,
        ]);
    }
}
