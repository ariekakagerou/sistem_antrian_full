<!-- Daftar Antrian Pasien -->
<div class="space-y-6" wire:poll.5s>
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-users mr-2 text-indigo-600"></i>
                    Daftar Antrian
                </h1>
                <p class="text-gray-600">Kelola antrian pasien di loket Anda</p>
            </div>
            <button wire:click="refreshAntrians" 
                    class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold px-6 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
            </button>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-700 text-sm font-semibold mb-1">Menunggu</p>
                    <p class="text-3xl font-bold text-yellow-600">
                        {{ collect($antrians ?? [])->where('status', 'menunggu')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-500 bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-700 text-sm font-semibold mb-1">Dipanggil</p>
                    <p class="text-3xl font-bold text-green-600">
                        {{ collect($antrians ?? [])->where('status', 'dipanggil')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-500 bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-700 text-sm font-semibold mb-1">Selesai</p>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ collect($antrians ?? [])->where('status', 'selesai')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Antrian -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list mr-2 text-indigo-600"></i>
                    Semua Antrian
                </h2>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-sm text-gray-600">Live Update</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            No. Antrian
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Pasien
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Loket
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Waktu Daftar
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($antrians ?? [] as $antrian)
                        <tr class="hover:bg-gray-50 transition-colors {{ $antrian['status'] === 'dipanggil' ? 'bg-green-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-2xl font-bold text-indigo-600">
                                    {{ $antrian['nomor_antrian'] ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $antrian['nama_pasien'] ?? '-' }}</p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-phone mr-1"></i>
                                        {{ $antrian['nomor_hp'] ?? '-' }}
                                    </p>
                                    @if(isset($antrian['keluhan']))
                                        <p class="text-xs text-gray-400 mt-1 line-clamp-1">
                                            <i class="fas fa-notes-medical mr-1"></i>
                                            {{ $antrian['keluhan'] }}
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">
                                    {{ $antrian['loket']['nama_loket'] ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($antrian['status'] === 'menunggu')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold inline-flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        Menunggu
                                    </span>
                                @elseif($antrian['status'] === 'dipanggil')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold inline-flex items-center animate-pulse">
                                        <i class="fas fa-bullhorn mr-1"></i>
                                        Dipanggil
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold inline-flex items-center">
                                        <i class="fas fa-check mr-1"></i>
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ isset($antrian['created_at']) ? \Carbon\Carbon::parse($antrian['created_at'])->format('H:i') : '-' }}
                                <br>
                                <span class="text-xs text-gray-400">
                                    {{ isset($antrian['created_at']) ? \Carbon\Carbon::parse($antrian['created_at'])->diffForHumans() : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @if($antrian['status'] === 'menunggu')
                                        <button wire:click="panggilAntrian({{ $antrian['id'] }})"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 transform hover:scale-105">
                                            <i class="fas fa-bullhorn mr-1"></i>
                                            Panggil
                                        </button>
                                    @elseif($antrian['status'] === 'dipanggil')
                                        <button wire:click="selesaiAntrian({{ $antrian['id'] }})"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 transform hover:scale-105">
                                            <i class="fas fa-check mr-1"></i>
                                            Selesai
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Terlayani
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-inbox text-5xl mb-3"></i>
                                    <p class="text-lg font-semibold">Belum ada antrian</p>
                                    <p class="text-sm mt-1">Antrian akan muncul di sini secara otomatis</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span>Total: <strong>{{ count($antrians ?? []) }}</strong> antrian</span>
                <span>
                    <i class="fas fa-sync-alt mr-1"></i>
                    Diperbarui otomatis setiap 5 detik
                </span>
            </div>
        </div>
    </div>
</div>
