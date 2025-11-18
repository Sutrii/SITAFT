<?php

namespace App\Http\Controllers;

use App\Models\JadwalDosen;
use App\Models\Jadwal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index($month = null, $year = null)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $today = Carbon::now()->locale('id')->isoFormat('dddd');
        $currentMonth = Carbon::createFromDate($year, $month)->translatedFormat('F Y');

        $dosenKosong = JadwalDosen::with('dosen')
            ->whereRaw('LOWER(TRIM(hari)) = LOWER(TRIM(?))', [$today])
            ->where('status', 'Kosong')
            ->get()
            ->groupBy('userId');

        $jadwalSeminar = Jadwal::with(['mahasiswa', 'skripsi'])
            ->whereYear('jadwal_seminar', $year)
            ->whereMonth('jadwal_seminar', $month)
            ->whereDate('jadwal_seminar', '>=', Carbon::today())
            ->orderBy('jadwal_seminar', 'asc')
            ->get();

        $eventDays = $jadwalSeminar->mapWithKeys(fn($j) => [
            Carbon::parse($j->jadwal_seminar)->day => $j->status
        ]);

        return view('dashboard.index', compact('dosenKosong', 'jadwalSeminar', 'eventDays', 'currentMonth'));
    }

    public function fetchMonth($month, $year)
    {
        $jadwalSeminar = Jadwal::with(['mahasiswa', 'skripsi'])
            ->whereYear('jadwal_seminar', $year)
            ->whereMonth('jadwal_seminar', $month)
            ->whereDate('jadwal_seminar', '>=', Carbon::today())
            ->orderBy('jadwal_seminar', 'asc')
            ->get();

        return response()->json($jadwalSeminar);
    }

    public function fetchData($month, $day, $year)
    {
        $selectedDate = Carbon::createFromDate($year, $month, $day ?? Carbon::now()->day);
        $hari = ucfirst($selectedDate->locale('id')->isoFormat('dddd'));

        $dosenKosong = JadwalDosen::with('dosen')
            ->whereRaw('LOWER(TRIM(hari)) = LOWER(TRIM(?))', [$hari])
            ->where('status', 'Kosong')
            ->get()
            ->groupBy('userId');

        $jadwalSeminar = Jadwal::with(['mahasiswa', 'skripsi'])
            ->whereYear('jadwal_seminar', $year)
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

    public function pengujiStats()
    {
        $penguji1 = Jadwal::with('dosen1')
            ->whereNotNull('dosenId1')
            ->get()
            ->groupBy('dosenId1')
            ->map(function ($group) {
                return [
                    'nama' => $group->first()->dosen1->name ?? 'Tidak Diketahui',
                    'jumlah' => $group->count()
                ];
            })
            ->values();

        $penguji2 = Jadwal::with('dosen2')
            ->whereNotNull('dosenId2')
            ->get()
            ->groupBy('dosenId2')
            ->map(function ($group) {
                return [
                    'nama' => $group->first()->dosen2->name ?? 'Tidak Diketahui',
                    'jumlah' => $group->count()
                ];
            })
            ->values();

        return response()->json([
            'penguji1' => $penguji1,
            'penguji2' => $penguji2
        ]);
    }
}
