<div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-indigo-900 text-white" 
     x-data="{ autoRefresh: true }" 
     x-init="
        setInterval(() => { 
            if (autoRefresh) {
                $wire.refresh();
            }
        }, 5000);
        setInterval(() => {
            document.getElementById('current-time').textContent = new Date().toLocaleTimeString('id-ID');
        }, 1000);
     ">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 shadow-2xl">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="bg-white rounded-full p-2 sm:p-3">
                        <i class="fas fa-hospital text-blue-600 text-2xl sm:text-3xl"></i>
                    </div>
                    <div class="text-center sm:text-left">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">Rumah Sakit Sehat Selalu</h1>
                        <p class="text-blue-100 text-sm sm:text-base md:text-lg">Silakan perhatikan nomor antrian Anda</p>
                    </div>
                </div>
                <div class="text-center sm:text-right">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-bold" id="current-time">{{ now()->format('H:i:s') }}</div>
                    <div class="text-blue-100 text-sm sm:text-base md:text-lg">{{ now()->format('d F Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        @if(count($antriansPerLoket) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($antriansPerLoket as $data)
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden border-2 border-white/20 transform transition-all hover:scale-105">
                        <!-- Header Loket -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-4 sm:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl sm:text-2xl font-bold">{{ $data['loket']['nama_loket'] }}</h2>
                                    <p class="text-blue-100 text-xs sm:text-sm">{{ $data['loket']['deskripsi'] }}</p>
                                </div>
                                <div class="bg-white/20 rounded-full p-3 sm:p-4">
                                    <i class="fas fa-hospital text-2xl sm:text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Antrian Aktif -->
                        <div class="p-4 sm:p-6 md:p-8">
                            @if($data['antrian_aktif'])
                                <div class="text-center mb-4 sm:mb-6">
                                    <p class="text-gray-300 text-base sm:text-lg mb-2 sm:mb-3">Sedang Dilayani</p>
                                    <div class="bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-xl animate-pulse-slow">
                                        <div class="text-5xl sm:text-6xl md:text-7xl font-bold text-white mb-2 sm:mb-4">
                                            {{ $data['antrian_aktif']['nomor_antrian'] }}
                                        </div>
                                        <div class="text-white text-base sm:text-lg md:text-xl font-semibold">
                                            {{ $data['antrian_aktif']['nama_pasien'] }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center mb-4 sm:mb-6">
                                    <p class="text-gray-300 text-base sm:text-lg mb-2 sm:mb-3">Sedang Dilayani</p>
                                    <div class="bg-white/5 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 border-2 border-dashed border-white/20">
                                        <i class="fas fa-inbox text-3xl sm:text-4xl md:text-5xl text-gray-500 mb-2 sm:mb-3"></i>
                                        <p class="text-gray-400 text-base sm:text-lg">Tidak ada antrian</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Info Menunggu -->
                            <div class="flex items-center justify-center space-x-2 sm:space-x-3 bg-white/5 rounded-lg sm:rounded-xl p-3 sm:p-4">
                                <i class="fas fa-users text-yellow-400 text-xl sm:text-2xl"></i>
                                <div class="text-center">
                                    <p class="text-gray-300 text-xs sm:text-sm">Antrian Menunggu</p>
                                    <p class="text-2xl sm:text-3xl font-bold text-yellow-400">{{ $data['jumlah_menunggu'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 sm:py-16 md:py-20">
                <div class="inline-block bg-white/10 backdrop-blur-lg rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-12 border-2 border-white/20">
                    <i class="fas fa-hospital text-4xl sm:text-5xl md:text-6xl text-gray-400 mb-3 sm:mb-4"></i>
                    <p class="text-xl sm:text-2xl text-gray-300">Tidak ada loket tersedia</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Footer Info -->
    <div class="fixed bottom-0 left-0 right-0 bg-gradient-to-r from-blue-600 to-indigo-600 shadow-2xl">
        <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-0">
                <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-6">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-xs sm:text-sm">Auto Refresh: 5 detik</span>
                    </div>
                    <button wire:click="refresh" 
                            class="bg-white/20 hover:bg-white/30 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold transition-colors">
                        <i class="fas fa-sync-alt mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">Refresh Manual</span>
                        <span class="sm:hidden">Refresh</span>
                    </button>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <a href="/" class="bg-white/20 hover:bg-white/30 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold transition-colors">
                        <i class="fas fa-home mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">Halaman Utama</span>
                        <span class="sm:hidden">Home</span>
                    </a>
                    <a href="/petugas" class="bg-white/20 hover:bg-white/30 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold transition-colors">
                        <i class="fas fa-user-shield mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">Login Petugas</span>
                        <span class="sm:hidden">Login</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacing untuk footer -->
    <div class="h-24 sm:h-20"></div>
</div>
