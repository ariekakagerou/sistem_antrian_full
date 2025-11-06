<!-- Riwayat Pelayanan -->
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-history mr-2 text-indigo-600"></i>
                    Riwayat Pelayanan
                </h1>
                <p class="text-gray-600">Daftar pasien yang sudah dilayani</p>
            </div>
            <button wire:click="exportRiwayat" 
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-file-excel mr-2"></i>
                Export Excel
            </button>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>
                    Cari Pasien
                </label>
                <input type="text" 
                       wire:model.live="searchRiwayat" 
                       placeholder="Cari nama pasien atau nomor antrian..."
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors">
            </div>

            <!-- Filter Tanggal -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-1"></i>
                    Filter Tanggal
                </label>
                <input type="date" 
                       wire:model.live="filterTanggal" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors">
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                            No. Antrian
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                            Nama Pasien
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                            Loket
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                            Waktu Mulai
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                            Waktu Selesai
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                            Durasi
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                            Petugas
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($riwayatPelayanan ?? [] as $riwayat)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xl font-bold text-indigo-600">
                                    {{ $riwayat['nomor_antrian'] ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $riwayat['nama_pasien'] ?? '-' }}</p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-phone mr-1"></i>
                                        {{ $riwayat['nomor_hp'] ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">
                                    {{ $riwayat['loket']['nama_loket'] ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-clock mr-1 text-green-600"></i>
                                {{ isset($riwayat['waktu_panggil']) ? \Carbon\Carbon::parse($riwayat['waktu_panggil'])->format('H:i:s') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-check-circle mr-1 text-blue-600"></i>
                                {{ isset($riwayat['waktu_selesai']) ? \Carbon\Carbon::parse($riwayat['waktu_selesai'])->format('H:i:s') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(isset($riwayat['waktu_panggil']) && isset($riwayat['waktu_selesai']))
                                    @php
                                        $mulai = \Carbon\Carbon::parse($riwayat['waktu_panggil']);
                                        $selesai = \Carbon\Carbon::parse($riwayat['waktu_selesai']);
                                        $durasi = $mulai->diffInMinutes($selesai);
                                    @endphp
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        {{ $durasi }} menit
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-user-nurse mr-1"></i>
                                {{ $riwayat['petugas_nama'] ?? $petugasNama ?? 'Petugas' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-inbox text-5xl mb-3"></i>
                                    <p class="text-lg font-semibold">Belum ada riwayat pelayanan</p>
                                    <p class="text-sm mt-1">Riwayat akan muncul setelah pasien selesai dilayani</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($riwayatPelayanan) && count($riwayatPelayanan) > 0)
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan <strong>{{ count($riwayatPelayanan) }}</strong> dari <strong>{{ $totalRiwayat ?? count($riwayatPelayanan) }}</strong> riwayat
                    </div>
                    
                    <!-- Pagination Buttons (Placeholder) -->
                    <div class="flex items-center space-x-2">
                        <button wire:click="previousPageRiwayat" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ ($currentPageRiwayat ?? 1) <= 1 ? 'disabled' : '' }}>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="px-4 py-2 bg-gray-200 rounded-lg font-semibold">
                            {{ $currentPageRiwayat ?? 1 }}
                        </span>
                        <button wire:click="nextPageRiwayat" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Statistik Riwayat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Dilayani Hari Ini</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ count($riwayatPelayanan ?? []) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-indigo-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Rata-rata Durasi</p>
                    <p class="text-3xl font-bold text-green-600">
                        @if(isset($riwayatPelayanan) && count($riwayatPelayanan) > 0)
                            @php
                                $totalDurasi = 0;
                                $count = 0;
                                foreach($riwayatPelayanan as $r) {
                                    if(isset($r['waktu_panggil']) && isset($r['waktu_selesai'])) {
                                        $mulai = \Carbon\Carbon::parse($r['waktu_panggil']);
                                        $selesai = \Carbon\Carbon::parse($r['waktu_selesai']);
                                        $totalDurasi += $mulai->diffInMinutes($selesai);
                                        $count++;
                                    }
                                }
                                $rataRata = $count > 0 ? round($totalDurasi / $count) : 0;
                            @endphp
                            {{ $rataRata }}
                        @else
                            0
                        @endif
                        <span class="text-lg text-gray-600">menit</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Waktu Tercepat</p>
                    <p class="text-3xl font-bold text-blue-600">
                        @if(isset($riwayatPelayanan) && count($riwayatPelayanan) > 0)
                            @php
                                $minDurasi = PHP_INT_MAX;
                                foreach($riwayatPelayanan as $r) {
                                    if(isset($r['waktu_panggil']) && isset($r['waktu_selesai'])) {
                                        $mulai = \Carbon\Carbon::parse($r['waktu_panggil']);
                                        $selesai = \Carbon\Carbon::parse($r['waktu_selesai']);
                                        $durasi = $mulai->diffInMinutes($selesai);
                                        if($durasi < $minDurasi) $minDurasi = $durasi;
                                    }
                                }
                                $tercepat = $minDurasi != PHP_INT_MAX ? $minDurasi : 0;
                            @endphp
                            {{ $tercepat }}
                        @else
                            0
                        @endif
                        <span class="text-lg text-gray-600">menit</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-tachometer-alt text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>
</div>
