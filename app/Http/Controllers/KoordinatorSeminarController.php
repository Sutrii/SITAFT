<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranSeminar;
use Illuminate\Http\Request;

class KoordinatorSeminarController extends Controller
{
    /**
     * Display a listing of the seminar requests.
     */
    public function index()
    {
        // Get all pengajuans with relationships
        $pengajuans = PendaftaranSeminar::with(['mahasiswa', 'skripsi.dosen1', 'skripsi.dosen2'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.koordinator.request-seminar', compact('pengajuans'));
    }

    /**
     * Update the status of a seminar request (ACC, Revisi, Tolak).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:acc,revisi,ditolak',
            'keterangan' => 'nullable|string'
        ]);

        $pengajuan = PendaftaranSeminar::findOrFail($id);
        
        $pengajuan->status = $request->status;
        
        // Save the note if it's a revisi/rejection or clear it if ACC
        if ($request->has('keterangan')) {
            $pengajuan->catatan = $request->keterangan;
        } elseif ($request->status == 'acc') {
            $pengajuan->catatan = null; // Clear note on acc
        }

        $pengajuan->save();

        $message = '';
        if ($request->status == 'acc') {
            $message = 'Pengajuan seminar berhasil disetujui.';
        } elseif ($request->status == 'revisi') {
            $message = 'Catatan revisi berhasil dikirim ke mahasiswa.';
        } else {
            $message = 'Pengajuan seminar telah ditolak.';
        }

        return redirect()->back()->with('success', $message);
    }
}
