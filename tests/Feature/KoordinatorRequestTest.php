<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Skripsi;
use App\Models\PendaftaranSeminar;

class KoordinatorRequestTest extends TestCase
{
    use RefreshDatabase;

    private function getKoordinator()
    {
        return User::factory()->create([
            'roleId' => 2, // assume koordinator or any admin
            'positionId' => 1 
        ]);
    }

    public function test_koordinator_can_view_requests()
    {
        $koordinator = $this->getKoordinator();

        $this->withoutExceptionHandling();
        $response = $this->actingAs($koordinator)->get(route('koordinator.request-seminar'));

        $response->assertStatus(200);
    }

    public function test_koordinator_can_acc_request()
    {
        $koordinator = $this->getKoordinator();

        $mahasiswa = Mahasiswa::create([
            'userId' => User::factory()->create()->id,
            'name' => 'John',
            'nim' => '123'
        ]);

        $dosen1 = \App\Models\Dosen::create([
            'userId' => User::factory()->create()->id,
            'name' => 'Dosen 1'
        ]);
        $dosen2 = \App\Models\Dosen::create([
            'userId' => User::factory()->create()->id,
            'name' => 'Dosen 2'
        ]);

        $skripsi = Skripsi::create([
            'nama_mahasiswa' => 'John',
            'judul_skripsi' => 'Skripsi',
            'dosen_pembimbing_1' => $dosen1->id,
            'dosen_pembimbing_2' => $dosen2->id,
        ]);

        $pengajuan = PendaftaranSeminar::create([
            'mahasiswa_id' => $mahasiswa->id,
            'skripsi_id' => $skripsi->id,
            'nomor_registrasi' => 'REG-1',
            'no_hp' => '123',
            'jenis_seminar' => 'seminar_proposal',
            'status' => 'pending',
            'file_persyaratan' => json_encode([]),
        ]);

        $response = $this->actingAs($koordinator)->post(route('koordinator.request-seminar.update-status', $pengajuan->id), [
            'status' => 'acc'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pengajuan seminar berhasil disetujui.');

        $this->assertDatabaseHas('pendaftaran_seminar', [
            'id' => $pengajuan->id,
            'status' => 'acc',
            'catatan' => null
        ]);
    }
    
    public function test_koordinator_can_revisi_request()
    {
        $koordinator = $this->getKoordinator();

        $mahasiswa = Mahasiswa::create([
            'userId' => User::factory()->create()->id,
            'name' => 'John',
            'nim' => '123'
        ]);

        $dosen1 = \App\Models\Dosen::create([
            'userId' => User::factory()->create()->id,
            'name' => 'Dosen 1'
        ]);
        $dosen2 = \App\Models\Dosen::create([
            'userId' => User::factory()->create()->id,
            'name' => 'Dosen 2'
        ]);

        $skripsi = Skripsi::create([
            'nama_mahasiswa' => 'John',
            'judul_skripsi' => 'Skripsi',
            'dosen_pembimbing_1' => $dosen1->id,
            'dosen_pembimbing_2' => $dosen2->id,
        ]);

        $pengajuan = PendaftaranSeminar::create([
            'mahasiswa_id' => $mahasiswa->id,
            'skripsi_id' => $skripsi->id,
            'nomor_registrasi' => 'REG-1',
            'no_hp' => '123',
            'jenis_seminar' => 'seminar_proposal',
            'status' => 'pending',
            'file_persyaratan' => json_encode([]),
        ]);

        $response = $this->actingAs($koordinator)->post(route('koordinator.request-seminar.update-status', $pengajuan->id), [
            'status' => 'revisi',
            'keterangan' => 'File draft proposal kurang lengkap'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Catatan revisi berhasil dikirim ke mahasiswa.');

        $this->assertDatabaseHas('pendaftaran_seminar', [
            'id' => $pengajuan->id,
            'status' => 'revisi',
            'catatan' => 'File draft proposal kurang lengkap'
        ]);
    }
}
