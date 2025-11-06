<!-- Form Pendaftaran Pasien Lengkap -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 md:p-8 mb-4 sm:mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-3">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
            <i class="fas fa-edit mr-2 text-blue-600"></i>
            Form Pendaftaran Pasien
        </h2>
        @if(isset($estimasiWaktu) && $estimasiWaktu)
            <div class="bg-blue-50 px-3 sm:px-4 py-2 rounded-lg">
                <p class="text-xs sm:text-sm text-gray-600">Estimasi Waktu Tunggu</p>
                <p class="text-base sm:text-lg font-bold text-blue-600">{{ $estimasiWaktu }} menit</p>
            </div>
        @endif
    </div>
    
    <form wire:submit.prevent="daftarPasien">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Lengkap -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Nama Lengkap Pasien <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       wire:model="nama_pasien" 
                       placeholder="Masukkan nama lengkap pasien"
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('nama_pasien') border-red-500 @enderror">
                @error('nama_pasien')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- NIK/KTP -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-id-card mr-2 text-blue-600"></i>
                    Nomor Identitas (NIK/KTP) <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       wire:model="nik" 
                       placeholder="16 digit NIK"
                       maxlength="16"
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('nik') border-red-500 @enderror">
                @error('nik')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor Rekam Medis -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-file-medical mr-2 text-blue-600"></i>
                    Nomor Rekam Medis <span class="text-gray-400 text-xs">(Opsional)</span>
                </label>
                <input type="text" 
                       wire:model="no_rekam_medis" 
                       placeholder="Kosongkan jika pasien baru"
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:outline-none transition-colors">
                <p class="text-xs text-gray-500 mt-1">Untuk pasien lama yang sudah pernah berobat</p>
            </div>

            <!-- Jenis Kelamin -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-venus-mars mr-2 text-blue-600"></i>
                    Jenis Kelamin <span class="text-red-500">*</span>
                </label>
                <select wire:model="jenis_kelamin" 
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('jenis_kelamin') border-red-500 @enderror">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-birthday-cake mr-2 text-blue-600"></i>
                    Tanggal Lahir <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       wire:model="tanggal_lahir" 
                       max="{{ date('Y-m-d') }}"
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('tanggal_lahir') border-red-500 @enderror">
                @error('tanggal_lahir')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor HP -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-phone mr-2 text-blue-600"></i>
                    Nomor HP/Kontak Darurat <span class="text-red-500">*</span>
                </label>
                <input type="tel" 
                       wire:model="nomor_hp" 
                       placeholder="08xxxxxxxxxx"
                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('nomor_hp') border-red-500 @enderror">
                @error('nomor_hp')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Dokter/Poli Tujuan -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-user-md mr-2 text-blue-600"></i>
                    Dokter/Poli Tujuan <span class="text-red-500">*</span>
                </label>
                <select wire:model="poli_tujuan" 
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('poli_tujuan') border-red-500 @enderror">
                    <option value="">Pilih Poli/Dokter</option>
                    <option value="Poli Umum">Poli Umum</option>
                    <option value="Poli Gigi">Poli Gigi</option>
                    <option value="Poli Anak">Poli Anak</option>
                    <option value="Poli Kandungan">Poli Kandungan</option>
                    <option value="Poli Mata">Poli Mata</option>
                    <option value="Poli THT">Poli THT</option>
                    <option value="Poli Jantung">Poli Jantung</option>
                    <option value="Poli Penyakit Dalam">Poli Penyakit Dalam</option>
                    <option value="Poli Bedah">Poli Bedah</option>
                    <option value="Poli Kulit">Poli Kulit & Kelamin</option>
                </select>
                @error('poli_tujuan')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat Lengkap -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                    Alamat Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="alamat" 
                          rows="3" 
                          placeholder="Masukkan alamat lengkap (Jalan, RT/RW, Kelurahan, Kecamatan, Kota)"
                          class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('alamat') border-red-500 @enderror"></textarea>
                @error('alamat')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Keluhan -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-notes-medical mr-2 text-blue-600"></i>
                    Keluhan/Gejala <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="keluhan" 
                          rows="4" 
                          placeholder="Jelaskan keluhan atau gejala yang Anda alami secara detail"
                          class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:outline-none transition-colors @error('keluhan') border-red-500 @enderror"></textarea>
                @error('keluhan')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Info Estimasi -->
        @if(isset($jumlahAntrian) && $jumlahAntrian > 0)
            <div class="mt-4 sm:mt-6 bg-yellow-50 border-l-4 border-yellow-500 p-3 sm:p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 text-lg sm:text-xl mr-2 sm:mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-sm sm:text-base font-semibold text-gray-800">Informasi Antrian</p>
                        <p class="text-xs sm:text-sm text-gray-600">Saat ini ada <strong>{{ $jumlahAntrian }} pasien</strong> yang sedang menunggu di loket ini. Estimasi waktu tunggu sekitar <strong>{{ $estimasiWaktu ?? ($jumlahAntrian * 5) }} menit</strong>.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Submit Button -->
        <button type="submit" 
                wire:loading.attr="disabled"
                class="w-full mt-4 sm:mt-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 sm:py-4 px-4 sm:px-6 text-sm sm:text-base rounded-lg sm:rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
            <span wire:loading.remove>
                <i class="fas fa-paper-plane mr-2"></i>
                Daftar Sekarang
            </span>
            <span wire:loading>
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Memproses Pendaftaran...
            </span>
        </button>

        <p class="text-center text-xs sm:text-sm text-gray-500 mt-3 sm:mt-4">
            <i class="fas fa-shield-alt mr-1"></i>
            Data Anda aman dan terlindungi sesuai kebijakan privasi rumah sakit
        </p>
    </form>
</div>
