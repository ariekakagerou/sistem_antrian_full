<!-- Pemanggilan Pasien -->
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-bullhorn mr-2 text-indigo-600"></i>
            Pemanggilan Pasien
        </h1>
        <p class="text-gray-600">Panggil dan kelola pasien yang sedang menunggu</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Panel Pemanggilan -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-user-plus mr-2 text-indigo-600"></i>
                Panggil Pasien Berikutnya
            </h2>

            @if($antrianBerikutnya)
                <!-- Preview Pasien Berikutnya -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-300 rounded-xl p-6 mb-6">
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-600 mb-2">Pasien Berikutnya:</p>
                        <div class="text-5xl font-bold text-indigo-600 mb-3">
                            {{ $antrianBerikutnya['nomor_antrian'] }}
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user text-indigo-600"></i>
                            <div>
                                <p class="text-xs text-gray-500">Nama Pasien</p>
                                <p class="font-semibold text-gray-800">{{ $antrianBerikutnya['nama_pasien'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-indigo-600"></i>
                            <div>
                                <p class="text-xs text-gray-500">Nomor HP</p>
                                <p class="font-semibold text-gray-800">{{ $antrianBerikutnya['nomor_hp'] }}</p>
                            </div>
                        </div>
                        @if(isset($antrianBerikutnya['keluhan']))
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-notes-medical text-indigo-600 mt-1"></i>
                                <div>
                                    <p class="text-xs text-gray-500">Keluhan</p>
                                    <p class="text-sm text-gray-700">{{ $antrianBerikutnya['keluhan'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tombol Panggil -->
                <button wire:click="panggilPasienBerikutnya" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-bullhorn mr-2 text-xl"></i>
                    <span class="text-lg">Panggil Pasien Ini</span>
                </button>

                <!-- Info Tambahan -->
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pasien akan dipanggil dan ditampilkan di layar display
                    </p>
                </div>
            @else
                <!-- Tidak Ada Antrian -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Antrian</h3>
                    <p class="text-gray-500">Semua pasien sudah dipanggil atau belum ada yang mendaftar</p>
                </div>
            @endif
        </div>

        <!-- Panel Pasien Sedang Dipanggil -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-user-check mr-2 text-green-600"></i>
                Pasien Sedang Dilayani
            </h2>

            @if($antrianAktif)
                <!-- Display Pasien Aktif -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-4 border-green-500 rounded-xl p-8 mb-6 animate-pulse-slow">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-500 rounded-full mb-3">
                            <i class="fas fa-bullhorn text-3xl text-white"></i>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Nomor Antrian:</p>
                        <div class="text-6xl font-bold text-green-600 mb-4">
                            {{ $antrianAktif['nomor_antrian'] }}
                        </div>
                    </div>

                    <div class="space-y-4 bg-white bg-opacity-50 rounded-lg p-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Nama Pasien</p>
                            <p class="text-xl font-bold text-gray-800">{{ $antrianAktif['nama_pasien'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Nomor HP</p>
                            <p class="font-semibold text-gray-700">{{ $antrianAktif['nomor_hp'] }}</p>
                        </div>
                        @if(isset($antrianAktif['keluhan']))
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Keluhan</p>
                                <p class="text-sm text-gray-700">{{ $antrianAktif['keluhan'] }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Waktu Dipanggil</p>
                            <p class="text-sm text-gray-700">
                                <i class="fas fa-clock mr-1"></i>
                                {{ isset($antrianAktif['waktu_panggil']) ? \Carbon\Carbon::parse($antrianAktif['waktu_panggil'])->format('H:i:s') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Selesai -->
                <button wire:click="selesaiAntrian({{ $antrianAktif['id'] }})"
                        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-check-circle mr-2 text-xl"></i>
                    <span class="text-lg">Selesai Pelayanan</span>
                </button>

                <!-- Tombol Panggil Ulang (Opsional) -->
                <button wire:click="panggilUlang({{ $antrianAktif['id'] }})"
                        class="w-full mt-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                    <i class="fas fa-redo mr-2"></i>
                    Panggil Ulang
                </button>
            @else
                <!-- Tidak Ada Pasien Aktif -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-slash text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Pasien Aktif</h3>
                    <p class="text-gray-500">Panggil pasien berikutnya untuk memulai pelayanan</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Antrian Menunggu -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-users mr-2 text-indigo-600"></i>
            Daftar Pasien Menunggu
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($antrianMenunggu ?? [] as $antrian)
                <div class="bg-gray-50 hover:bg-gray-100 border-2 border-gray-200 rounded-lg p-4 transition-all duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl font-bold text-indigo-600">{{ $antrian['nomor_antrian'] }}</span>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                            Menunggu
                        </span>
                    </div>
                    <p class="font-semibold text-gray-800 mb-1">{{ $antrian['nama_pasien'] }}</p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        {{ isset($antrian['created_at']) ? \Carbon\Carbon::parse($antrian['created_at'])->diffForHumans() : '-' }}
                    </p>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Tidak ada pasien menunggu</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Text-to-Speech (Opsional - Dikomentari) -->
    <!--
    <script>
        // Text-to-Speech untuk pemanggilan pasien
        window.addEventListener('panggil-pasien', event => {
            const nomorAntrian = event.detail.nomor_antrian;
            const namaPasien = event.detail.nama_pasien;
            const loket = event.detail.loket;
            
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(
                    `Nomor antrian ${nomorAntrian}, ${namaPasien}, silakan menuju ${loket}`
                );
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                speechSynthesis.speak(utterance);
            }
        });
    </script>
    -->
</div>
