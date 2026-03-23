<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MahasiswaSeminarTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_can_view_daftar_seminar_page()
    {
        $user = User::factory()->create(['roleId' => 1, 'positionId' => 3]);
        $mahasiswa = Mahasiswa::create([
            'userId' => $user->id,
            'name' => $user->name,
            'nim' => '12345678',
        ]);

        $this->withoutExceptionHandling();
        $response = $this->actingAs($user)->get(route('mahasiswa.daftar-seminar'));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_can_register_seminar_proposal()
    {
        Storage::fake('public');

        $user = User::factory()->create(['roleId' => 1, 'positionId' => 3]);
        $mahasiswa = Mahasiswa::create([
            'userId' => $user->id,
            'name' => 'John Doe',
            'nim' => '12345678',
        ]);

        $dosen1 = Dosen::create([
            'userId' => $user->id, // just placeholder
            'name' => 'Dosen Pembimbing 1',
        ]);

        $dosen2 = Dosen::create([
            'userId' => $user->id,
            'name' => 'Dosen Pembimbing 2',
        ]);

        $this->withoutExceptionHandling();
        $response = $this->actingAs($user)->post(route('mahasiswa.daftar-seminar.proposal'), [
            'nip' => '12345678',
            'nama' => 'John Doe',
            'pembimbing_1' => $dosen1->id,
            'pembimbing_2' => $dosen2->id,
            'judul_skripsi' => 'Pengembangan Sistem Berbasis AI',
            'file_krs' => UploadedFile::fake()->create('krs.pdf', 100, 'application/pdf'),
            'file_pengesahan' => UploadedFile::fake()->create('pengesahan.pdf', 100, 'application/pdf'),
            'file_draft_proposal' => UploadedFile::fake()->create('draft.pdf', 100, 'application/pdf'),
            'no_hp' => '081234567890',
            'no_registrasi' => 'REG-001'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pendaftaran seminar proposal berhasil disubmit.');

        $this->assertDatabaseHas('skripsi', [
            'nama_mahasiswa' => 'John Doe',
            'judul_skripsi' => 'Pengembangan Sistem Berbasis AI',
            'dosen_pembimbing_1' => $dosen1->id,
            'dosen_pembimbing_2' => $dosen2->id,
        ]);

        $this->assertDatabaseHas('pendaftaran_seminar', [
            'mahasiswa_id' => $mahasiswa->id,
            'nomor_registrasi' => 'REG-001',
            'jenis_seminar' => 'seminar_proposal',
            'status' => 'pending'
        ]);
        
        $pendaftaran = \App\Models\PendaftaranSeminar::where('nomor_registrasi', 'REG-001')->first();
        $files = json_decode($pendaftaran->file_persyaratan, true);
        
        Storage::disk('public')->assertExists($files['krs']);
        Storage::disk('public')->assertExists($files['pengesahan']);
        Storage::disk('public')->assertExists($files['draft_proposal']);
    }
}
