<!-- Tabel Riwayat Antrian Aktif dengan Real-time Update -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 md:p-8 mb-4 sm:mb-6" wire:poll.5s>
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-2">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
            <i class="fas fa-list-alt mr-2 text-blue-600"></i>
            Riwayat Antrian Aktif
        </h2>
        <div class="flex items-center space-x-2">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            <span class="text-xs sm:text-sm text-gray-600 font-semibold">Live Update</span>
        </div>
    </div>

    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <table class="w-full min-w-[640px]">
            <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                <tr>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold uppercase rounded-tl-xl">No</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold uppercase">Nomor Antrian</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold uppercase">Nama Pasien</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold uppercase">Loket</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold uppercase">Status</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-bold uppercase rounded-tr-xl">Waktu Daftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($riwayatAntrian ?? [] as $index => $antrian)
                    <tr class="hover:bg-blue-50 transition-colors {{ $antrian['status'] === 'dipanggil' ? 'bg-green-50' : '' }}">
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700 font-semibold">{{ $index + 1 }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <span class="text-xl sm:text-2xl font-bold text-blue-600">{{ $antrian['nomor_antrian'] ?? '-' }}</span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-bold text-gray-800">{{ $antrian['nama_pasien'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-phone mr-1"></i>
                                        {{ $antrian['nomor_hp'] ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <span class="px-2 sm:px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs sm:text-sm font-semibold">
                                <i class="fas fa-door-open mr-1"></i>
                                {{ $antrian['loket']['nama_loket'] ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if(isset($antrian['status']))
                                @if($antrian['status'] === 'menunggu')
                                    <span class="px-2 sm:px-4 py-1 sm:py-2 bg-yellow-100 text-yellow-800 rounded-full text-xs sm:text-sm font-bold inline-flex items-center">
                                        <i class="fas fa-clock mr-2"></i>
                                        Menunggu
                                    </span>
                                @elseif($antrian['status'] === 'dipanggil')
                                    <span class="px-2 sm:px-4 py-1 sm:py-2 bg-green-100 text-green-800 rounded-full text-xs sm:text-sm font-bold inline-flex items-center animate-pulse">
                                        <i class="fas fa-bullhorn mr-2"></i>
                                        Dipanggil
                                    </span>
                                @else
                                    <span class="px-2 sm:px-4 py-1 sm:py-2 bg-blue-100 text-blue-800 rounded-full text-xs sm:text-sm font-bold inline-flex items-center">
                                        <i class="fas fa-check mr-2"></i>
                                        Selesai
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-600">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-clock text-blue-600 text-sm"></i>
                                <span class="text-sm sm:text-base font-semibold">
                                    {{ isset($antrian['created_at']) ? \Carbon\Carbon::parse($antrian['created_at'])->format('H:i') : '-' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ isset($antrian['created_at']) ? \Carbon\Carbon::parse($antrian['created_at'])->diffForHumans() : '' }}
                            </p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 sm:px-6 py-12 sm:py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-4xl sm:text-5xl md:text-6xl mb-3 sm:mb-4 text-gray-300"></i>
                                <p class="text-base sm:text-lg font-semibold text-gray-600">Belum ada antrian aktif</p>
                                <p class="text-xs sm:text-sm mt-2 text-gray-500">Daftar sekarang untuk mendapatkan nomor antrian</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($riwayatAntrian) && count($riwayatAntrian) > 0)
        <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200 pt-3 sm:pt-4 gap-2">
            <div class="text-xs sm:text-sm text-gray-600">
                <i class="fas fa-users mr-1 text-blue-600"></i>
                Total <strong>{{ count($riwayatAntrian) }}</strong> antrian aktif
            </div>
            <div class="text-xs sm:text-sm text-gray-500">
                <i class="fas fa-sync-alt mr-1 animate-spin"></i>
                Data diperbarui otomatis setiap 5 detik
            </div>
        </div>
    @endif

    <!-- Legend Status -->
    <div class="mt-4 sm:mt-6 bg-gray-50 rounded-lg sm:rounded-xl p-3 sm:p-4">
        <p class="text-xs sm:text-sm font-semibold text-gray-700 mb-2 sm:mb-3">
            <i class="fas fa-info-circle mr-1 text-blue-600"></i>
            Keterangan Status:
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-3">
            <div class="flex items-center space-x-2">
                <span class="px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold flex-shrink-0">
                    <i class="fas fa-clock mr-1"></i>
                    Menunggu
                </span>
                <span class="text-xs text-gray-600">Pasien sedang menunggu dipanggil</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                    <i class="fas fa-bullhorn mr-1"></i>
                    Dipanggil
                </span>
                <span class="text-xs text-gray-600">Pasien sedang dilayani</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                    <i class="fas fa-check mr-1"></i>
                    Selesai
                </span>
                <span class="text-xs text-gray-600">Pelayanan selesai</span>
            </div>
        </div>
    </div>
</div>
