<!-- Modal Sukses dengan QR Code dan Tombol Cetak -->
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
                    <!-- Left Side: Info -->
                    <div>
                        <div class="text-center mb-4 sm:mb-6">
                            <p class="text-xs sm:text-sm text-gray-600 mb-2">Nomor Antrian Anda</p>
                            <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 shadow-lg">
                                <div class="text-4xl sm:text-5xl md:text-6xl font-bold text-blue-600 mb-2 sm:mb-4">
                                    {{ $antrianBaru['nomor_antrian'] ?? ($antrianBaru['no_antrian'] ?? ($antrianBaru['nomorAntrian'] ?? '-')) }}
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 space-y-2 sm:space-y-3">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-user text-blue-600 mt-0.5 text-sm sm:text-base"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500">Nama Pasien</p>
                                    <p class="text-sm sm:text-base font-bold text-gray-800">{{ $antrianBaru['nama_pasien'] ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-door-open text-blue-600 mt-1"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500">Loket</p>
                                    <p class="font-bold text-gray-800">{{ $antrianBaru['loket']['nama_loket'] ?? '-' }}</p>
                                </div>
                            </div>

                            @if(isset($antrianBaru['poli_tujuan']))
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-user-md text-blue-600 mt-1"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500">Poli Tujuan</p>
                                    <p class="font-bold text-gray-800">{{ $antrianBaru['poli_tujuan'] }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="flex items-start space-x-3">
                                <i class="fas fa-calendar text-blue-600 mt-1"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500">Tanggal & Waktu</p>
                                    <p class="font-bold text-gray-800">
                                        {{ isset($antrianBaru['created_at']) ? \Carbon\Carbon::parse($antrianBaru['created_at'])->format('d M Y, H:i') : date('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500">Status</p>
                                    <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">
                                        <i class="fas fa-clock mr-1"></i>
                                        Menunggu
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: QR Code -->
                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-lg">
                            <div class="mb-3 text-center">
                                <p class="text-sm font-semibold text-gray-700">Scan QR Code</p>
                                <p class="text-xs text-gray-500">Untuk informasi antrian</p>
                            </div>
                            
                            <!-- QR Code Placeholder -->
                            <div class="w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 bg-white border-2 sm:border-4 border-gray-200 rounded-lg sm:rounded-xl flex items-center justify-center">
                                @php
                                    $nomorAntrianView = $antrianBaru['nomor_antrian'] ?? ($antrianBaru['no_antrian'] ?? ($antrianBaru['nomorAntrian'] ?? null));
                                @endphp
                                @if(isset($nomorAntrianView))
                                    {{-- QR Code akan di-generate di sini --}}
                                    <div class="text-center">
                                        <i class="fas fa-qrcode text-4xl sm:text-5xl md:text-6xl text-gray-400 mb-2"></i>
                                        <p class="text-xs text-gray-500">QR Code</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $nomorAntrianView }}</p>
                                    </div>
                                    {{-- 
                                    Untuk menggunakan QR Code generator, install package:
                                    composer require simplesoftwareio/simple-qrcode
                                    
                                    Kemudian ganti div di atas dengan:
                                    {!! QrCode::size(180)->generate(route('antrian.detail', $antrianBaru['id'])) !!}
                                    --}}
                                @endif
                            </div>

                            <p class="text-xs text-center text-gray-500 mt-3">
                                Simpan QR Code ini untuk tracking
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Estimasi Waktu -->
                @if(isset($estimasiWaktu) && $estimasiWaktu)
                    <div class="mt-4 sm:mt-6 bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 border-2 border-blue-300">
                        <div class="flex items-center justify-center space-x-2 sm:space-x-3">
                            <i class="fas fa-hourglass-half text-blue-600 text-xl sm:text-2xl"></i>
                            <div class="text-center">
                                <p class="text-xs sm:text-sm text-gray-600">Estimasi Waktu Tunggu</p>
                                <div class="text-white text-base sm:text-lg md:text-xl font-semibold text-blue-600">{{ $estimasiWaktu }} menit</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Informasi Penting -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-lg sm:text-xl mr-2 sm:mr-3 mt-1"></i>
                    <div>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 mb-2">Informasi Penting:</p>
                        <ul class="text-xs sm:text-sm text-gray-700 space-y-1">
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Harap datang 15 menit sebelum waktu estimasi</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Perhatikan layar display untuk nomor antrian Anda</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Bawa dokumen identitas (KTP/SIM) saat pendaftaran</li>
                            <li><i class="fas fa-check text-green-600 mr-2"></i>Simpan tiket ini sebagai bukti pendaftaran</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                <!-- Cetak Tiket -->
                <button wire:click="cetakTiket" 
                        class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 text-sm sm:text-base rounded-lg sm:rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                    <i class="fas fa-print"></i>
                    <span>Cetak Tiket</span>
                </button>

                <!-- Download PDF -->
                <button wire:click="downloadTiket" 
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 text-sm sm:text-base rounded-lg sm:rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                    <i class="fas fa-download"></i>
                    <span>Download PDF</span>
                </button>

                <!-- Kirim WhatsApp -->
                <button wire:click="kirimWhatsApp" 
                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 text-sm sm:text-base rounded-lg sm:rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                    <i class="fab fa-whatsapp"></i>
                    <span>Kirim WA</span>
                </button>
            </div>

            <!-- Close Button -->
            <button wire:click="closeSuccess" 
                    class="w-full mt-4 sm:mt-6 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 text-sm sm:text-base rounded-lg sm:rounded-xl transition-colors flex items-center justify-center space-x-2">
                <i class="fas fa-times mr-2"></i>
                Tutup
            </button>

            <!-- Footer Note -->
            <p class="text-center text-xs text-gray-500 mt-4">
                <i class="fas fa-info-circle mr-1"></i>
                Untuk informasi lebih lanjut, hubungi customer service kami
            </p>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #tiket-antrian, #tiket-antrian * {
                visibility: visible;
            }
            #tiket-antrian {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
@endif
