<x-app-layout>
    <div class="flex min-h-screen bg-[#fbfdfe]" id="main-container">


        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm flex items-center justify-between px-8 py-4">
                <div class="flex items-center gap-4">

                    <div>
                        <h2 class="text-xl font-semibold text-gray-700">Pendaftaran Seminar</h2>
                        <p class="text-sm text-gray-500 mt-1">Pilih jenis seminar yang ingin Anda daftar</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="user-menu" type="button" class="flex items-center space-x-3 focus:outline-none group">
                            <span class="text-gray-600 font-medium group-hover:text-green-700 transition">
                                {{ Auth::user()->name ?? 'User' }}
                            </span>
                            <img class="w-9 h-9 rounded-full border border-green-200"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=16a34a&color=fff"
                                alt="avatar">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-green-700 transition" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="user-dropdown"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-800 rounded-t-lg">Profil</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-b-lg">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-auto p-8">


                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('dashboard.mahasiswa') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Dashboard
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Seminar Proposal Status -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Seminar Proposal</h3>
                                @if($registrationStatus['seminar_proposal'])
                                    <p class="text-green-600 text-sm font-medium mt-1">Sudah Terdaftar</p>
                                @else
                                    <p class="text-gray-500 text-sm mt-1">Belum Terdaftar</p>
                                @endif
                            </div>
                            <div class="text-blue-500 bg-blue-50 p-3 rounded-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-6 h-10">Daftarkan rencana penelitian Anda untuk dievaluasi oleh dosen penguji.</p>
                        
                        @if($registrationStatus['seminar_proposal'])
                            <button disabled class="w-full bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed">
                                Sudah Terdaftar
                            </button>
                        @else
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition" onclick="document.getElementById('modal-seminar-proposal').classList.remove('hidden')">
                                Daftar Sekarang
                            </button>
                        @endif
                    </div>

                    <!-- Seminar Hasil Status -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-yellow-500 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Seminar Hasil</h3>
                                @if($registrationStatus['seminar_hasil'])
                                    <p class="text-green-600 text-sm font-medium mt-1">Sudah Terdaftar</p>
                                @else
                                    <p class="text-gray-500 text-sm mt-1">Belum Terdaftar</p>
                                @endif
                            </div>
                            <div class="text-yellow-500 bg-yellow-50 p-3 rounded-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-6 h-10">Daftarkan hasil dari penelitian skripsi Anda sebelum maju ke sidang akhir.</p>
                        
                        @if($registrationStatus['seminar_hasil'])
                            <button disabled class="w-full bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed">
                                Sudah Terdaftar
                            </button>
                        @elseif(!$registrationStatus['seminar_proposal'] || $registrationStatus['seminar_proposal']->status != 'acc')
                            <button disabled title="Selesaikan Seminar Proposal terlebih dahulu" class="w-full bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed">
                                Proposal Belum Selesai
                            </button>
                        @else
                            <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition" onclick="document.getElementById('modal-seminar-hasil').classList.remove('hidden')">
                                Daftar Sekarang
                            </button>
                        @endif
                    </div>

                    <!-- Sidang Status -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-red-500 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Sidang Akhir</h3>
                                @if($registrationStatus['sidang_akhir'])
                                    <p class="text-green-600 text-sm font-medium mt-1">Sudah Terdaftar</p>
                                @else
                                    <p class="text-gray-500 text-sm mt-1">Belum Terdaftar</p>
                                @endif
                            </div>
                            <div class="text-red-500 bg-red-50 p-3 rounded-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-6 h-10">Daftarkan sidang akhir sebagai tahap final dari tugas akhir Anda.</p>
                        
                        @if($registrationStatus['sidang_akhir'])
                            <button disabled class="w-full bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed">
                                Sudah Terdaftar
                            </button>
                        @else
                            <button class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition" onclick="alert('Fitur pendaftaran dalam pengembangan')">
                                Daftar Sekarang
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Modal Pendaftaran Seminar Proposal -->
                <div id="modal-seminar-proposal" class="hidden fixed inset-0 z-50 overflow-y-auto">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="document.getElementById('modal-seminar-proposal').classList.add('hidden')"></div>

                    <!-- Modal panel -->
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                        <div class="relative bg-[#fdf5e6] rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-3xl w-full border border-gray-100">
                            
                            <!-- Header Form Style -->
                            <div class="bg-white p-6 border-t-[10px] border-orange-500 rounded-lg mx-6 mt-6 shadow-sm">
                                <h2 class="text-3xl font-normal text-gray-900 mb-2">Pendaftaran Seminar Proposal</h2>
                                <p class="text-sm text-gray-600 mb-4">Berikut Merupakan pendaftaran seminar proposal. Lakukan pengisian pendaftaran dengan sebenar-benarnya kesalahan menjadi tanggung jawab masing-masing.</p>
                            </div>

                            <form action="{{ route('mahasiswa.daftar-seminar.proposal') }}" method="POST" enctype="multipart/form-data" class="bg-transparent p-6">
                                @csrf
                                <div class="space-y-6">
                                    <!-- NIM -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="nip" class="block mb-4 font-medium text-gray-800">NIM <span class="text-red-500">*</span></label>
                                        <input type="text" id="nip" name="nip" value="{{ $mahasiswa->nip ?? Auth::user()->nip }}"readonly required class="w-1/2 border-0 border-b border-gray-300 bg-transparent px-0 py-2 focus:ring-0 focus:border-blue-600 text-gray-900 border-dotted" placeholder="Jawaban Anda">
                                    </div>

                                    <!-- Nama -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="nama" class="block mb-4 font-medium text-gray-800">Nama <span class="text-red-500">*</span></label>
                                        <input type="text" id="nama" name="nama" value="{{ $mahasiswa->name ?? Auth::user()->name }}"readonly required class="w-1/2 border-0 border-b border-gray-300 bg-transparent px-0 py-2 focus:ring-0 focus:border-blue-600 text-gray-900 border-dotted" placeholder="Jawaban Anda">
                                    </div>

                                    <!-- Pembimbing 1 -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="pembimbing_1" class="block mb-4 font-medium text-gray-800">Pembimbing 1 <span class="text-red-500">*</span></label>
                                        <select id="pembimbing_1" name="pembimbing_1" required class="w-1/3 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                            <option value="" disabled selected>Pilih</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Pembimbing 2 -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="pembimbing_2" class="block mb-4 font-medium text-gray-800">Pembimbing 2 <span class="text-red-500">*</span></label>
                                        <select id="pembimbing_2" name="pembimbing_2" required class="w-1/3 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                            <option value="" disabled selected>Pilih</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Judul Tugas Akhir -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="judul_skripsi" class="block mb-4 font-medium text-gray-800">Judul Tugas Akhir <span class="text-red-500">*</span></label>
                                        <input type="text" id="judul_skripsi" name="judul_skripsi" required class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-2 focus:ring-0 focus:border-blue-600 text-gray-900 border-dotted" placeholder="Jawaban Anda">
                                    </div>

                                    <!-- KRS Online Terakhir -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="file_krs" class="block mb-2 font-medium text-gray-800">Upload KRS Online Terakhir</label>
                                        <p class="text-xs text-gray-500 mb-4">Upload 1 file yang didukung: PDF. Maks 10 MB.</p>
                                        <div class="flex items-center space-x-2 border border-gray-300 rounded px-4 py-2 w-max cursor-pointer hover:bg-gray-50 text-blue-600 font-medium text-sm bg-white" onclick="document.getElementById('file_krs').click()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span>Tambahkan file</span>
                                        </div>
                                        <input type="file" id="file_krs" name="file_krs" accept=".pdf" class="hidden">
                                        <div id="file_krs_name" class="text-sm text-gray-600 mt-2"></div>
                                    </div>

                                    <!-- Lembar Pengesahan -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="file_pengesahan" class="block mb-2 font-medium text-gray-800">Upload Lembar Pengesahan <span class="text-red-500">*</span></label>
                                        <p class="text-xs text-gray-500 mb-4">Upload 1 file yang didukung: PDF. Maks 10 MB.</p>
                                        <div class="flex items-center space-x-2 border border-gray-300 rounded px-4 py-2 w-max cursor-pointer hover:bg-gray-50 text-blue-600 font-medium text-sm bg-white" onclick="document.getElementById('file_pengesahan').click()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span>Tambahkan file</span>
                                        </div>
                                        <input type="file" id="file_pengesahan" name="file_pengesahan" accept=".pdf" required class="hidden">
                                        <div id="file_pengesahan_name" class="text-sm text-gray-600 mt-2"></div>
                                    </div>

                                    <!-- Draft Proposal Tugas Akhir -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="file_draft_proposal" class="block mb-2 font-medium text-gray-800">Upload Draft Proposal Tugas Akhir <span class="text-red-500">*</span></label>
                                        <p class="text-xs text-gray-500 mb-4">Upload 1 file yang didukung: PDF. Maks 10 MB.</p>
                                        <div class="flex items-center space-x-2 border border-gray-300 rounded px-4 py-2 w-max cursor-pointer hover:bg-gray-50 text-blue-600 font-medium text-sm bg-white" onclick="document.getElementById('file_draft_proposal').click()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span>Tambahkan file</span>
                                        </div>
                                        <input type="file" id="file_draft_proposal" name="file_draft_proposal" accept=".pdf" required class="hidden">
                                        <div id="file_draft_proposal_name" class="text-sm text-gray-600 mt-2"></div>
                                    </div>

                                    <!-- No HP/WA -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="no_hp" class="block mb-1 font-medium text-gray-800">No HP/WA <span class="text-red-500">*</span></label>
                                        <p class="text-sm text-gray-600 mb-4">(Data Akan disimpan secara rahasia)</p>
                                        <input type="text" id="no_hp" name="no_hp" required class="w-1/2 border-0 border-b border-gray-300 bg-transparent px-0 py-2 focus:ring-0 focus:border-blue-600 text-gray-900 border-dotted" placeholder="Jawaban Anda">
                                    </div>

                                    <!-- Nomor Registrasi -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="no_registrasi" class="block mb-4 font-medium text-gray-800">Nomor Registrasi <span class="text-red-500">*</span></label>
                                        <input type="text" id="no_registrasi" name="no_registrasi" required class="w-1/2 border-0 border-b border-gray-300 bg-transparent px-0 py-2 focus:ring-0 focus:border-blue-600 text-gray-900 border-dotted" placeholder="Jawaban Anda">
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="mt-8 flex items-center justify-between">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Kirim
                                    </button>
                                    <button type="button" onclick="document.getElementById('modal-seminar-proposal').classList.add('hidden')" class="text-green-600 hover:text-green-800 font-medium text-sm transition">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Pendaftaran Seminar Hasil -->
                <div id="modal-seminar-hasil" class="hidden fixed inset-0 z-50 overflow-y-auto">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="document.getElementById('modal-seminar-hasil').classList.add('hidden')"></div>

                    <!-- Modal panel -->
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                        <div class="relative bg-[#fdf5e6] rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-3xl w-full border border-gray-100">
                            
                            <!-- Header Form Style -->
                            <div class="bg-white p-6 border-t-[10px] border-yellow-500 rounded-lg mx-6 mt-6 shadow-sm">
                                <h2 class="text-3xl font-normal text-gray-900 mb-4">Pendaftaran Seminar Hasil</h2>
                                <p class="text-sm text-gray-600 mb-2">Pastikan data skripsi yang diimpor dari kegiatan Seminar Proposal sudah sesuai, tekan tombol Edit (pensil) jika ada perubahan.</p>
                            </div>

                            <form action="{{ route('mahasiswa.daftar-seminar.hasil') }}" method="POST" enctype="multipart/form-data" class="bg-transparent p-6">
                                @csrf
                                <div class="space-y-6">
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="no_registrasi_hasil" class="block mb-4 font-medium text-gray-800">Nomor Registrasi <span class="text-red-500">*</span></label>
                                        <input type="text" id="no_registrasi_hasil" name="no_registrasi" required class="w-1/2 border-0 border-b border-gray-300 bg-transparent px-0 py-2 focus:ring-0 focus:border-yellow-600 text-gray-900 border-dotted" placeholder="Jawaban Anda">
                                    </div>

                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="nip_hasil" class="block mb-4 font-medium text-gray-800">NIM <span class="text-red-500">*</span></label>
                                        <input type="text" id="nip_hasil" name="nip" value="{{ $mahasiswa->nim ?? Auth::user()->nip }}" readonly required class="w-1/2 border-0 border-b border-gray-300 bg-transparent px-0 py-2 focus:ring-0 focus:border-yellow-600 text-gray-900 border-dotted" placeholder="Jawaban Anda">
                                    </div>

                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="nama_hasil" class="block mb-4 font-medium text-gray-800">Nama <span class="text-red-500">*</span></label>
                                        <input type="text" id="nama_hasil" name="nama" value="{{ $mahasiswa->name ?? Auth::user()->name }}" readonly required class="w-1/2 border-0 border-b border-gray-300 bg-transparent px-0 py-2 focus:ring-0 focus:border-yellow-600 text-gray-900 border-dotted" placeholder="Jawaban Anda">
                                    </div>

                                    <!-- Pembimbing 1 with Edit toggle -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 relative group">
                                        <div class="flex justify-between items-center mb-4">
                                            <label for="pembimbing_1_hasil" class="block font-medium text-gray-800">Pembimbing 1 <span class="text-red-500">*</span></label>
                                            <button type="button" onclick="toggleEdit('pembimbing_1_hasil')" class="text-gray-400 hover:text-yellow-600" title="Edit Pembimbing 1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </div>
                                        <select id="pembimbing_1_hasil" name="pembimbing_1" required disabled class="w-1/3 border border-gray-300 bg-gray-100 text-gray-900 text-sm rounded-md focus:ring-yellow-500 focus:border-yellow-500 block p-2.5">
                                            <option value="" disabled>Pilih</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}" {{ isset($skripsi) && $skripsi->dosen_pembimbing_1 == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="pembimbing_1" id="pembimbing_1_hasil_hidden" value="{{ $skripsi->dosen_pembimbing_1 ?? '' }}">
                                    </div>

                                    <!-- Pembimbing 2 with Edit toggle -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 relative group">
                                        <div class="flex justify-between items-center mb-4">
                                            <label for="pembimbing_2_hasil" class="block font-medium text-gray-800">Pembimbing 2 <span class="text-red-500">*</span></label>
                                            <button type="button" onclick="toggleEdit('pembimbing_2_hasil')" class="text-gray-400 hover:text-yellow-600" title="Edit Pembimbing 2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </div>
                                        <select id="pembimbing_2_hasil" name="pembimbing_2" required disabled class="w-1/3 border border-gray-300 bg-gray-100 text-gray-900 text-sm rounded-md focus:ring-yellow-500 focus:border-yellow-500 block p-2.5">
                                            <option value="" disabled>Pilih</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}" {{ isset($skripsi) && $skripsi->dosen_pembimbing_2 == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="pembimbing_2" id="pembimbing_2_hasil_hidden" value="{{ $skripsi->dosen_pembimbing_2 ?? '' }}">
                                    </div>

                                    <!-- Penguji 1 with Edit toggle -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 relative group">
                                        <div class="flex justify-between items-center mb-4">
                                            <label for="penguji_1_hasil" class="block font-medium text-gray-800">Penguji 1 <span class="text-red-500">*</span></label>
                                            <button type="button" onclick="toggleEdit('penguji_1_hasil')" class="text-gray-400 hover:text-yellow-600" title="Edit Penguji 1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </div>
                                        <select id="penguji_1_hasil" name="penguji_1" required disabled class="w-1/3 border border-gray-300 bg-gray-100 text-gray-900 text-sm rounded-md focus:ring-yellow-500 focus:border-yellow-500 block p-2.5">
                                            <option value="" disabled selected>Pilih</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}" {{ (isset($skripsi) && $skripsi->dosen_penguji_1 == $dosen->id) || (isset($jadwalTerakhir) && $jadwalTerakhir->dosenId1 == $dosen->id) ? 'selected' : '' }}>{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="penguji_1" id="penguji_1_hasil_hidden" value="{{ $skripsi->dosen_penguji_1 ?? ($jadwalTerakhir->dosenId1 ?? '') }}">
                                    </div>

                                    <!-- Penguji 2 with Edit toggle -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 relative group">
                                        <div class="flex justify-between items-center mb-4">
                                            <label for="penguji_2_hasil" class="block font-medium text-gray-800">Penguji 2 <span class="text-red-500">*</span></label>
                                            <button type="button" onclick="toggleEdit('penguji_2_hasil')" class="text-gray-400 hover:text-yellow-600" title="Edit Penguji 2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </div>
                                        <select id="penguji_2_hasil" name="penguji_2" required disabled class="w-1/3 border border-gray-300 bg-gray-100 text-gray-900 text-sm rounded-md focus:ring-yellow-500 focus:border-yellow-500 block p-2.5">
                                            <option value="" disabled selected>Pilih</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}" {{ (isset($skripsi) && $skripsi->dosen_penguji_2 == $dosen->id) || (isset($jadwalTerakhir) && $jadwalTerakhir->dosenId2 == $dosen->id) ? 'selected' : '' }}>{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="penguji_2" id="penguji_2_hasil_hidden" value="{{ $skripsi->dosen_penguji_2 ?? ($jadwalTerakhir->dosenId2 ?? '') }}">
                                    </div>

                                    <!-- Judul Tugas Akhir with Edit toggle -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 relative group">
                                        <div class="flex justify-between items-center mb-4">
                                            <label for="judul_skripsi_hasil" class="block font-medium text-gray-800">Judul Tugas Akhir (Draft Skripsi) <span class="text-red-500">*</span></label>
                                            <button type="button" onclick="toggleEdit('judul_skripsi_hasil')" class="text-gray-400 hover:text-yellow-600" title="Edit Judul Skripsi">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </div>
                                        <input type="text" id="judul_skripsi_hasil" name="judul_skripsi" value="{{ $skripsi->judul_skripsi ?? '' }}" readonly required class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-2 focus:ring-0 focus:border-yellow-600 text-gray-900 border-dotted" placeholder="Jawaban Anda">
                                    </div>

                                    <!-- Bidang -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="bidang_hasil" class="block mb-4 font-medium text-gray-800">Bidang <span class="text-red-500">*</span></label>
                                        <div class="space-y-3">
                                            @foreach($bidangs as $index => $bidang)
                                            <div class="flex items-center">
                                                <input id="bidang-{{ $index }}" type="radio" value="{{ $bidang }}" name="bidang" {{ (isset($skripsi) && $skripsi->bidang == $bidang) ? 'checked' : ($index == 0 ? 'checked' : '') }} class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 focus:ring-yellow-500" required>
                                                <label for="bidang-{{ $index }}" class="ml-2 text-sm font-medium text-gray-900">{{ $bidang }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- KRS -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="file_krs_hasil" class="block mb-2 font-medium text-gray-800">Upload KRS Terakhir <span class="text-red-500">*</span></label>
                                        <p class="text-xs text-gray-500 mb-4">Upload 1 file yang didukung: PDF. Maks 10 MB.</p>
                                        <div class="flex items-center space-x-2 border border-gray-300 rounded px-4 py-2 w-max cursor-pointer hover:bg-gray-50 text-yellow-600 font-medium text-sm bg-white" onclick="document.getElementById('file_krs_hasil').click()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span>Tambahkan file</span>
                                        </div>
                                        <input type="file" id="file_krs_hasil" name="file_krs" accept=".pdf" class="hidden">
                                        <div id="file_krs_hasil_name" class="text-sm text-gray-600 mt-2"></div>
                                    </div>

                                    <!-- Persetujuan Seminar Hasil -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="file_persetujuan_hasil" class="block mb-2 font-medium text-gray-800">Upload Lembar Pengesahan <span class="text-red-500">*</span></label>
                                        <p class="text-xs text-gray-500 mb-4">Upload 1 file yang didukung: PDF. Maks 10 MB.</p>
                                        <div class="flex items-center space-x-2 border border-gray-300 rounded px-4 py-2 w-max cursor-pointer hover:bg-gray-50 text-blue-600 font-medium text-sm bg-white" onclick="document.getElementById('file_persetujuan_hasil').click()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span>Tambahkan file</span>
                                        </div>
                                        <input type="file" id="file_persetujuan_hasil" name="file_persetujuan_hasil" accept=".pdf" required class="hidden">
                                        <div id="file_persetujuan_hasil_name" class="text-sm text-gray-600 mt-2"></div>
                                    </div>

                                    <!-- Draft Skripsi -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <label for="file_draft_skripsi" class="block mb-2 font-medium text-gray-800">Upload Draft Skripsi (Lengkap) <span class="text-red-500">*</span></label>
                                        <p class="text-xs text-gray-500 mb-4">Upload 1 file yang didukung: PDF. Maks 10 MB.</p>
                                        <div class="flex items-center space-x-2 border border-gray-300 rounded px-4 py-2 w-max cursor-pointer hover:bg-gray-50 text-yellow-600 font-medium text-sm bg-white" onclick="document.getElementById('file_draft_skripsi').click()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span>Tambahkan file</span>
                                        </div>
                                        <input type="file" id="file_draft_skripsi" name="file_draft_skripsi" accept=".pdf" class="hidden">
                                        <div id="file_draft_skripsi_name" class="text-sm text-gray-600 mt-2"></div>
                                    </div>

                                </div>

                                <div class="mt-8 flex items-center justify-between">
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded transition focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                        Kirim
                                    </button>
                                    <button type="button" onclick="document.getElementById('modal-seminar-hasil').classList.add('hidden')" class="text-red-600 hover:text-red-800 font-medium text-sm transition">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}"
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terdapat Kesalahan!',
                html: `
                    <ul class="text-left text-sm text-red-600 list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `
            });
        @endif

        function toggleEdit(inputId) {
            const el = document.getElementById(inputId);
            if (el.tagName === 'SELECT') {
                if (el.disabled) {
                    el.disabled = false;
                    el.classList.remove('bg-gray-100');
                    document.getElementById(inputId + '_hidden').disabled = true; // disable hidden input so select takes over
                    el.focus();
                } else {
                    el.disabled = true;
                    el.classList.add('bg-gray-100');
                    document.getElementById(inputId + '_hidden').disabled = false;
                }
            } else {
                if (el.readOnly) {
                    el.readOnly = false;
                    el.classList.remove('border-dotted');
                    el.focus();
                } else {
                    el.readOnly = true;
                    el.classList.add('border-dotted');
                }
            }
        }

        // Update file names when selected
        ['file_krs', 'file_pengesahan', 'file_draft_proposal', 'file_krs_hasil', 'file_persetujuan_hasil', 'file_draft_skripsi'].forEach(id => {
            const input = document.getElementById(id);
            if(input) {
                input.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        document.getElementById(id + '_name').textContent = e.target.files[0].name;
                    }
                });
            }
        });

        // User Menu
        const userMenu = document.getElementById('user-menu');
        const userDropdown = document.getElementById('user-dropdown');

        userMenu?.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown?.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            userDropdown?.classList.add('hidden');
        });

        userDropdown?.addEventListener('click', (e) => {
            e.stopPropagation();
        });


    </script>
</x-app-layout>
