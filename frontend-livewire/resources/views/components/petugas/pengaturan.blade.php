<!-- Pengaturan Akun -->
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-cog mr-2 text-indigo-600"></i>
            Pengaturan Akun
        </h1>
        <p class="text-gray-600">Kelola informasi akun dan preferensi Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Pengaturan -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Profil -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-user-circle mr-2 text-indigo-600"></i>
                    Informasi Profil
                </h2>

                <form wire:submit.prevent="updateProfil">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user mr-1 text-indigo-600"></i>
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="profilNama" 
                                   placeholder="Masukkan nama lengkap"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors @error('profilNama') border-red-500 @enderror">
                            @error('profilNama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-1 text-indigo-600"></i>
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   wire:model="profilEmail" 
                                   placeholder="email@example.com"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors @error('profilEmail') border-red-500 @enderror">
                            @error('profilEmail')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor HP -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone mr-1 text-indigo-600"></i>
                                Nomor HP
                            </label>
                            <input type="tel" 
                                   wire:model="profilHP" 
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors">
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-id-badge mr-1 text-indigo-600"></i>
                                Jabatan
                            </label>
                            <input type="text" 
                                   wire:model="profilJabatan" 
                                   placeholder="Petugas Loket"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors">
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mt-6">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition-all duration-200 transform hover:scale-105 disabled:opacity-50">
                            <span wire:loading.remove>
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ubah Password -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-lock mr-2 text-indigo-600"></i>
                    Ubah Password
                </h2>

                <form wire:submit.prevent="updatePassword">
                    <div class="space-y-4">
                        <!-- Password Lama -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Lama <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   wire:model="passwordLama" 
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors @error('passwordLama') border-red-500 @enderror">
                            @error('passwordLama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   wire:model="passwordBaru" 
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors @error('passwordBaru') border-red-500 @enderror">
                            @error('passwordBaru')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Minimal 8 karakter, kombinasi huruf dan angka
                            </p>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   wire:model="passwordKonfirmasi" 
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors @error('passwordKonfirmasi') border-red-500 @enderror">
                            @error('passwordKonfirmasi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Ubah Password -->
                    <div class="mt-6">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition-all duration-200 transform hover:scale-105 disabled:opacity-50">
                            <span wire:loading.remove>
                                <i class="fas fa-key mr-2"></i>
                                Ubah Password
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Mengubah...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Akun & Aktivitas -->
        <div class="space-y-6">
            <!-- Info Akun -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                    Info Akun
                </h2>

                <div class="space-y-4">
                    <div class="pb-3 border-b border-gray-200">
                        <p class="text-xs text-gray-500 mb-1">ID Petugas</p>
                        <p class="font-semibold text-gray-800">{{ $petugasId ?? 'PTG-001' }}</p>
                    </div>
                    <div class="pb-3 border-b border-gray-200">
                        <p class="text-xs text-gray-500 mb-1">Status Akun</p>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>
                            Aktif
                        </span>
                    </div>
                    <div class="pb-3 border-b border-gray-200">
                        <p class="text-xs text-gray-500 mb-1">Terdaftar Sejak</p>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $terdaftarSejak ?? now()->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Login Terakhir</p>
                        <p class="font-semibold text-gray-800">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $loginTerakhir ?? now()->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistik Pribadi -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <h2 class="text-xl font-bold mb-4">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Statistik Anda
                </h2>

                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-indigo-400">
                        <span class="text-indigo-100">Total Pasien Dilayani</span>
                        <span class="text-2xl font-bold">{{ $totalPasienDilayani ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-indigo-400">
                        <span class="text-indigo-100">Hari Ini</span>
                        <span class="text-2xl font-bold">{{ $pasienHariIni ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-indigo-100">Minggu Ini</span>
                        <span class="text-2xl font-bold">{{ $pasienMingguIni ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Preferensi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-sliders-h mr-2 text-indigo-600"></i>
                    Preferensi
                </h2>

                <div class="space-y-4">
                    <!-- Notifikasi -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">Notifikasi Suara</p>
                            <p class="text-xs text-gray-500">Aktifkan suara saat ada antrian baru</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="notifikasiSuara" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <!-- Auto Refresh -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">Auto Refresh</p>
                            <p class="text-xs text-gray-500">Perbarui data otomatis</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="autoRefresh" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
