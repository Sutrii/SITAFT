<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Skripsi;
use App\Models\Jadwal;
use Carbon\Carbon;

class KoordinatorJadwalTest extends TestCase
{
    use RefreshDatabase;

    private function getKoordinator()
    {
        return User::factory()->create([
            'roleId' => 2, // assume non-mahasiswa role has access, based on JadwalController: if roleId=1 and positionId=3 (mahasiswa), abort.
            'positionId' => 1 
        ]);
    }

    public function test_koordinator_can_schedule_seminar()
    {
        $koordinator = $this->getKoordinator();

        $mahasiswa = Mahasiswa::create([
            'userId' => User::factory()->create()->id,
            'name' => 'Jane',
            'nim' => '999'
        ]);

        $dosen1 = Dosen::create([
            'userId' => User::factory()->create()->id,
            'name' => 'Penguji 1',
        ]);

        $dosen2 = Dosen::create([
            'userId' => User::factory()->create()->id,
            'name' => 'Penguji 2',
        ]);

        $skripsi = Skripsi::create([
            'nama_mahasiswa' => 'Jane',
            'judul_skripsi' => 'Skripsi Test',
            'dosen_pembimbing_1' => 1,
            'dosen_pembimbing_2' => 2,
        ]);

        $response = $this->actingAs($koordinator)->post(route('koordinator.jadwal.store'), [
            'skripsiId' => $skripsi->id,
            'mahasiswaId' => $mahasiswa->id,
            'dosenId1' => $dosen1->id,
            'dosenId2' => $dosen2->id,
            'tanggal_seminar' => now()->addDays(1)->format('Y-m-d'),
            'jam_seminar' => '08.00 - 08.50',
            'ruang' => 'Ruang Sidang 1',
            'status' => 'Seminar Proposal'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Jadwal tugas akhir berhasil ditambahkan!');

        $this->assertDatabaseHas('jadwal', [
            'skripsiId' => $skripsi->id,
            'mahasiswaId' => $mahasiswa->id,
            'dosenId1' => $dosen1->id,
            'dosenId2' => $dosen2->id,
            'ruang' => 'Ruang Sidang 1',
            'status' => 'Seminar Proposal'
        ]);
    }
}
