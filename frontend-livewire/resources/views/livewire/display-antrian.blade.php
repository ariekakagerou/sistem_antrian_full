<div class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white p-4">
    <!-- Header -->
    <header class="text-center mb-6">
        <h1 class="text-4xl font-bold mb-2">SISTEM ANTRIAN RUMAH SAKIT</h1>
        <div class="text-xl">{{ now()->format('l, d F Y') }} | {{ $currentTime }}</div>
    </header>

    <!-- Notification -->
    @if($showNotification)
        <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-8 py-4 rounded-lg shadow-lg z-50 animate-pulse">
            <div class="text-lg font-bold">{{ $notificationMessage }}</div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto">
        @if(count($antriansPerLoket) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($antriansPerLoket as $data)
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <!-- Header Loket -->
                        <div class="text-center mb-4">
                            <h2 class="text-2xl font-bold text-yellow-400">{{ $data['loket']['nama_loket'] }}</h2>
                            <p class="text-gray-300">{{ $data['loket']['deskripsi'] ?? '' }}</p>
                        </div>

                        <!-- Antrian Sedang Dipanggil -->
                        @if($data['antrian_aktif'])
                            <div class="bg-gradient-to-r from-green-600/30 to-green-500/30 rounded-lg p-6 mb-4 border-2 border-green-400/60 shadow-lg animate-pulse">
                                <div class="text-center">
                                    <p class="text-green-300 text-sm font-semibold mb-2">📢 SEDANG DIPANGGIL</p>
                                    <p class="text-5xl font-black text-green-400 mb-2">{{ $data['antrian_aktif']['nomor_antrian'] }}</p>
                                    <p class="text-white text-xl font-medium">{{ $data['antrian_aktif']['nama_pasien'] }}</p>
                                    <p class="text-green-200 text-sm mt-2">Silakan menuju {{ $data['loket']['nama_loket'] }}</p>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-600/20 rounded-lg p-6 mb-4 border border-gray-500/50">
                                <div class="text-center">
                                    <p class="text-gray-400 text-lg">📍 Menunggu Antrian</p>
                                    @if($data['jumlah_menunggu'] > 0)
                                        <p class="text-yellow-300 text-2xl font-bold mt-2">{{ $data['jumlah_menunggu'] }} Antrian</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Daftar Antrian Menunggu -->
                        @if($data['jumlah_menunggu'] > 0)
                            <div class="bg-gradient-to-br from-yellow-600/20 to-orange-600/20 rounded-lg p-4 border border-yellow-500/40">
                                <div class="text-center mb-4">
                                    <p class="text-yellow-300 text-sm font-semibold">⏳ ANTRIAN MENUNGGU ({{ $data['jumlah_menunggu'] }})</p>
                                </div>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach(array_slice($data['antrian_menunggu'], 0, 8) as $antrian)
                                        <div class="text-center bg-yellow-500/30 rounded-lg py-3 px-2 border border-yellow-400/50 hover:bg-yellow-500/40 transition-colors">
                                            <p class="text-yellow-200 font-bold text-lg">{{ $antrian['nomor_antrian'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                @if($data['jumlah_menunggu'] > 8)
                                    <div class="text-center mt-3">
                                        <p class="text-yellow-300 text-sm font-medium">... dan {{ $data['jumlah_menunggu'] - 8 }} antrian lagi</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <!-- Tidak Ada Antrian -->
            <div class="text-center py-12">
                <h2 class="text-3xl text-gray-300 mb-4">TIDAK ADA ANTRIAN SAAT INI</h2>
                <p class="text-lg text-gray-400">Semua loket sedang kosong</p>
            </div>
        @endif
    </main>
</div>

<script>
    // Auto-hide notification after 5 seconds
    document.addEventListener('livewire:init', () => {
        @if($showNotification)
            setTimeout(() => {
                @this.set('showNotification', false);
            }, 5000);
        @endif
        
        // Auto refresh setiap 5 detik untuk update data antrian
        setInterval(() => {
            @this.refresh();
        }, 5000);
        
        // Update waktu setiap detik
        setInterval(() => {
            @this.set('currentTime', new Date().toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            }));
        }, 1000);
    });
</script>
