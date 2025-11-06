<!-- Dashboard Ringkas -->
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-home mr-2 text-indigo-600"></i>
            Dashboard
        </h1>
        <p class="text-gray-600">Selamat datang di panel petugas loket</p>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Pasien Hari Ini -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-indigo-100 text-sm font-semibold">Total Pasien</p>
                    <p class="text-xs text-indigo-200">Hari Ini</p>
                </div>
            </div>
            <div class="text-4xl font-bold">{{ $totalPasienHariIni ?? 0 }}</div>
            <div class="mt-2 text-sm text-indigo-100">
                <i class="fas fa-calendar-day mr-1"></i>
                {{ now()->format('d M Y') }}
            </div>
        </div>

        <!-- Pasien Menunggu -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-yellow-100 text-sm font-semibold">Menunggu</p>
                    <p class="text-xs text-yellow-200">Antrian Aktif</p>
                </div>
            </div>
            <div class="text-4xl font-bold">{{ $jumlahMenunggu ?? 0 }}</div>
            <div class="mt-2 text-sm text-yellow-100">
                <i class="fas fa-hourglass-half mr-1"></i>
                Dalam antrian
            </div>
        </div>

        <!-- Pasien Sedang Dilayani -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-nurse text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-green-100 text-sm font-semibold">Dilayani</p>
                    <p class="text-xs text-green-200">Sedang Proses</p>
                </div>
            </div>
            <div class="text-4xl font-bold">{{ $jumlahDilayani ?? 0 }}</div>
            <div class="mt-2 text-sm text-green-100">
                <i class="fas fa-stethoscope mr-1"></i>
                Dalam pelayanan
            </div>
        </div>

        <!-- Pasien Selesai -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-blue-100 text-sm font-semibold">Selesai</p>
                    <p class="text-xs text-blue-200">Hari Ini</p>
                </div>
            </div>
            <div class="text-4xl font-bold">{{ $jumlahSelesai ?? 0 }}</div>
            <div class="mt-2 text-sm text-blue-100">
                <i class="fas fa-clipboard-check mr-1"></i>
                Terlayani
            </div>
        </div>
    </div>

    <!-- Quick Actions & Log Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-bolt mr-2 text-indigo-600"></i>
                Aksi Cepat
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <button wire:click="$set('activeMenu', 'pemanggilan')" 
                        class="bg-gradient-to-br from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white p-4 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-bullhorn text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Panggil Pasien</p>
                </button>
                <button wire:click="$set('activeMenu', 'daftar-antrian')" 
                        class="bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-4 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-list text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Lihat Antrian</p>
                </button>
                <button wire:click="$set('activeMenu', 'riwayat')" 
                        class="bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-4 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-history text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Riwayat</p>
                </button>
                <button wire:click="refreshData" 
                        class="bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white p-4 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-sync-alt text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Refresh Data</p>
                </button>
                <button wire:click="$set('activeMenu', 'pengaturan')" 
                        class="bg-gradient-to-br from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white p-4 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-cog text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Pengaturan</p>
                </button>
                <button onclick="window.open('/display', '_blank')" 
                        class="bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white p-4 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-tv text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Display</p>
                </button>
            </div>
        </div>

        <!-- Log Aktivitas -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-bell mr-2 text-indigo-600"></i>
                Log Aktivitas
            </h2>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($logAktivitas ?? [] as $log)
                    <div class="flex items-start space-x-3 pb-3 border-b border-gray-100 last:border-0">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-700">{{ $log['message'] ?? '' }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $log['time'] ?? '' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Grafik/Chart (Opsional - Placeholder) -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line mr-2 text-indigo-600"></i>
            Statistik Mingguan
        </h2>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center text-gray-400">
                <i class="fas fa-chart-bar text-5xl mb-3"></i>
                <p>Grafik statistik akan ditampilkan di sini</p>
                <p class="text-sm mt-2">(Integrasi Chart.js atau ApexCharts)</p>
            </div>
        </div>
    </div>
</div>
