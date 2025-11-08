<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-4 sm:py-6 md:py-8 px-3 sm:px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-blue-600 rounded-full mb-3 sm:mb-4">
                <i class="fas fa-hospital-user text-2xl sm:text-3xl text-white"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-2">Rumah Sakit Sehat Selalu</h1>
            <p class="text-sm sm:text-base text-gray-600">Silakan isi form di bawah untuk mendaftar antrian</p>
        </div>

        <!-- Pilih Loket Section -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 md:p-8 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-2">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                    <i class="fas fa-door-open mr-2 text-blue-600"></i>
                    Pilih Loket
                </h2>
                @if($totalPages > 1)
                    <div class="text-xs sm:text-sm text-gray-600">
                        Halaman {{ $currentPage }} dari {{ $totalPages }}
                    </div>
                @endif
            </div>

            <!-- Loket Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                @foreach($this->getPaginatedLokets() as $loket)
                    <button type="button"
                            wire:click="$set('loket_id', {{ $loket['id'] }})"
                            class="relative p-4 sm:p-6 rounded-lg sm:rounded-xl border-2 transition-all duration-200 hover:shadow-lg
                                   {{ $loket_id == $loket['id'] 
                                      ? 'border-blue-600 bg-blue-50 shadow-md' 
                                      : 'border-gray-200 hover:border-blue-400 bg-white' }}">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-full mb-2 sm:mb-3
                                        {{ $loket_id == $loket['id'] ? 'bg-blue-600' : 'bg-gradient-to-br from-blue-500 to-indigo-600' }}">
                                <i class="fas fa-hospital text-xl sm:text-2xl text-white"></i>
                            </div>
                            <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-1">
                                {{ $loket['nama_loket'] }}
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-600">
                                {{ $loket['deskripsi'] }}
                            </p>
                            @if($loket_id == $loket['id'])
                                <div class="absolute top-1 right-1 sm:top-2 sm:right-2">
                                    <i class="fas fa-check-circle text-blue-600 text-lg sm:text-xl"></i>
                                </div>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>

            <!-- Pagination Controls -->
            @if($totalPages > 1)
                <div class="flex flex-wrap items-center justify-center gap-1 sm:gap-2">
                    <!-- Previous Button -->
                    <button type="button"
                            wire:click="previousPage"
                            @if($currentPage == 1) disabled @endif
                            class="px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base font-semibold transition-colors
                                   {{ $currentPage == 1 
                                      ? 'bg-gray-200 text-gray-400 cursor-not-allowed' 
                                      : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                        <i class="fas fa-chevron-left mr-1"></i>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </button>

                    <!-- Page Numbers -->
                    <div class="flex gap-1 sm:gap-1">
                        @for($i = 1; $i <= $totalPages; $i++)
                            <button type="button"
                                    wire:click="goToPage({{ $i }})"
                                    class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg text-sm sm:text-base font-semibold transition-colors
                                           {{ $currentPage == $i 
                                              ? 'bg-blue-600 text-white' 
                                              : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                {{ $i }}
                            </button>
                        @endfor
                    </div>

                    <!-- Next Button -->
                    <button type="button"
                            wire:click="nextPage"
                            @if($currentPage == $totalPages) disabled @endif
                            class="px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base font-semibold transition-colors
                                   {{ $currentPage == $totalPages 
                                      ? 'bg-gray-200 text-gray-400 cursor-not-allowed' 
                                      : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                        <span class="hidden sm:inline">Selanjutnya</span>
                        <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            @endif

            @error('loket_id')
                <p class="text-red-500 text-sm mt-4 text-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Form Pendaftaran - Include Component -->
        @if($loket_id)
            @include('components.pasien.form-pendaftaran')
        @endif

        <!-- Tabel Riwayat Antrian - Include Component -->
        @include('components.pasien.tabel-riwayat')

        <!-- Info -->
        <div class="mt-4 sm:mt-6 text-center">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-0">
                <a href="/petugas" class="text-sm sm:text-base text-blue-600 hover:text-blue-800 font-semibold">
                    <i class="fas fa-user-shield mr-1"></i>
                    Login Petugas
                </a>
                <span class="hidden sm:inline mx-3 text-gray-400">|</span>
                <a href="/display" class="text-sm sm:text-base text-blue-600 hover:text-blue-800 font-semibold">
                    <i class="fas fa-tv mr-1"></i>
                    Lihat Display Antrian
                </a>
            </div>
        </div>
    </div>

    <!-- Debug Button - Hapus untuk production -->
    <div class="fixed bottom-4 right-4 z-40">
        <button wire:click="testModal" 
                class="bg-red-500 text-white px-3 py-2 rounded-lg shadow-lg hover:bg-red-600 text-sm">
            Test Modal
        </button>
    </div>

    <!-- Success Modal - Langsung di view utama -->
@if($showSuccess && $antrianBaru)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3 sm:p-4" 
         x-data="{ show: true }"
         x-show="show"
         x-transition>
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-3xl w-full p-4 sm:p-6 md:p-8 max-h-[90vh] overflow-y-auto">
            <!-- Header Success -->
            <div class="text-center mb-4 sm:mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-green-100 rounded-full mb-3 sm:mb-4 animate-bounce">
                    <i class="fas fa-check-circle text-3xl sm:text-4xl text-green-600"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Pendaftaran Berhasil!</h2>
                <p class="text-sm sm:text-base text-gray-600">Terima kasih telah mendaftar. Berikut adalah tiket antrian Anda</p>
            </div>

            <!-- Tiket Antrian Card -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 sm:border-4 border-blue-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 mb-4 sm:mb-6" id="tiket-antrian">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Kolom Kiri - Nomor Antrian -->
                    <div class="text-center md:text-left">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1 sm:mb-2">NOMOR ANTRIAN</p>
                        <p class="text-4xl sm:text-5xl md:text-6xl font-black text-blue-600 mb-2 sm:mb-3">{{ $antrianBaru['nomor_antrian'] ?? 'N/A' }}</p>
                        <p class="text-xs sm:text-sm text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $antrianBaru['waktu_pendaftaran'] ?? now()->format('H:i:s') }}
                        </p>
                    </div>

                    <!-- Kolom Kanan - Informasi Pasien -->
                    <div class="text-center md:text-right">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1 sm:mb-2">NAMA PASIEN</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">{{ $antrianBaru['nama_pasien'] ?? 'N/A' }}</p>
                        <div class="space-y-1 sm:space-y-2">
                            <p class="text-xs sm:text-sm text-gray-600">
                                <i class="fas fa-door-open mr-1"></i>
                                <strong>Loket:</strong> {{ $antrianBaru['loket'] ?? ($antrianBaru['nama_loket'] ?? 'N/A') }}
                            </p>
                            <p class="text-xs sm:text-sm text-gray-600">
                                <i class="fas fa-stethoscope mr-1"></i>
                                <strong>Poli:</strong> {{ $antrianBaru['poli_tujuan'] ?? 'Umum' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Estimasi Waktu -->
                <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-center">
                        <div class="bg-yellow-50 rounded-lg p-3 sm:p-4">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">ESTIMASI TUNGGU</p>
                            <p class="text-lg sm:text-xl font-bold text-yellow-600">{{ $antrianBaru['estimasi_waktu_tunggu'] ?? '30 menit' }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-3 sm:p-4">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">PERKIRAAN DIPANGGIL</p>
                            <p class="text-lg sm:text-xl font-bold text-green-600">{{ $antrianBaru['perkiraan_dipanggil'] ?? '14:50 WIB' }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3 sm:p-4">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">STATUS</p>
                            <p class="text-lg sm:text-xl font-bold text-blue-600">MENUNGGU</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                <button wire:click="cetakTiket" 
                        class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Tiket
                </button>
                <button wire:click="closeSuccess" 
                        class="flex items-center justify-center px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Tutup
                </button>
                <a href="/display" 
                   class="flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                    <i class="fas fa-tv mr-2"></i>
                    Lihat Display
                </a>
            </div>
        </div>
    </div>
@endif
</div>

@push('scripts')
<script>
    // Listen untuk event print-tiket
    document.addEventListener('livewire:init', () => {
        Livewire.on('print-tiket', () => {
            window.print();
        });

        Livewire.on('open-whatsapp', (event) => {
            const message = event.message || event[0]?.message;
            if (message) {
                window.open(`https://wa.me/?text=${message}`, '_blank');
            }
        });
    });
</script>
@endpush
