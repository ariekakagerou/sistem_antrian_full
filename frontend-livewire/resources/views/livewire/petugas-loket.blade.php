<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    @if(!$isLoggedIn)
        <!-- Login Form -->
        <div class="min-h-screen flex items-center justify-center px-4">
            <div class="max-w-md w-full">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-600 rounded-full mb-4">
                        <i class="fas fa-user-shield text-3xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Login Petugas</h1>
                    <p class="text-gray-600">Masuk untuk mengelola antrian</p>
                </div>

                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <form wire:submit.prevent="login">
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-envelope mr-2 text-indigo-600"></i>
                                Email
                            </label>
                            <input type="email" 
                                   wire:model="email" 
                                   placeholder="email@example.com"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-lock mr-2 text-indigo-600"></i>
                                Password
                            </label>
                            <input type="password" 
                                   wire:model="password" 
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:outline-none transition-colors @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors disabled:opacity-50">
                            <span wire:loading.remove>
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Memproses...
                            </span>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Atau login dengan</span>
                        </div>
                    </div>

                    <!-- Google Login Button -->
                    <a href="http://127.0.0.1:8000/auth/google" 
                       class="w-full flex items-center justify-center bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg border-2 border-gray-300 transition-colors">
                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Login dengan Google
                    </a>

                    <div class="mt-6 text-center">
                        <a href="/" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Halaman Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Panel Petugas dengan Sidebar -->
        <div class="flex" x-data="{ sidebarOpen: false }">
            <!-- Sidebar -->
            @include('components.petugas.sidebar')

            <!-- Main Content -->
            <div class="flex-1 lg:ml-64 p-3 sm:p-4 md:p-6">
                @if($selectedLoket)
                    <!-- Header Konten Utama -->
                    <div class="mb-4 sm:mb-6">
                        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg px-4 sm:px-6 py-3 sm:py-4">
                            <!-- Mobile Menu Button -->
                            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden mr-3 text-gray-600 hover:text-gray-800">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            
                            <div class="flex-1">
                                <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800">
                                    <i class="fas fa-layer-group text-indigo-600 mr-2"></i>
                                    {{ ['dashboard'=>'Dashboard','daftar-antrian'=>'Daftar Antrian','pemanggilan'=>'Pemanggilan Pasien','riwayat'=>'Riwayat Pelayanan','pengaturan'=>'Pengaturan Akun'][$activeMenu] ?? 'Dashboard' }}
                                </h1>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                    Loket: <span class="font-semibold text-gray-800">{{ $loketNama }}</span>
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="refreshData" class="inline-flex items-center px-3 sm:px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 text-sm sm:text-base">
                                    <i class="fas fa-sync-alt sm:mr-2"></i>
                                    <span class="hidden sm:inline">Refresh</span>
                                </button>
                                <button wire:click="selectLoket(null)" class="hidden sm:inline-flex items-center px-3 sm:px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 text-sm sm:text-base">
                                    <i class="fas fa-exchange-alt mr-2"></i>
                                    Ganti Loket
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!$selectedLoket)
                    <!-- Pilih Loket -->
                    <div class="max-w-6xl mx-auto">
                        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 md:p-8">
                            <div class="text-center mb-6 sm:mb-8">
                                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-indigo-100 rounded-full mb-3 sm:mb-4">
                                    <i class="fas fa-door-open text-2xl sm:text-3xl text-indigo-600"></i>
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Pilih Loket Anda</h2>
                                <p class="text-sm sm:text-base text-gray-600">Silakan pilih loket untuk memulai pelayanan</p>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                @foreach($this->getPaginatedLokets() as $loket)
                                    <button wire:click="selectLoket({{ $loket['id'] }})"
                                            class="bg-gradient-to-br from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white p-4 sm:p-6 md:p-8 rounded-lg sm:rounded-xl shadow-lg transition-all transform hover:scale-105">
                                        <i class="fas fa-hospital text-3xl sm:text-4xl md:text-5xl mb-3 sm:mb-4"></i>
                                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold mb-2">{{ $loket['nama_loket'] }}</h3>
                                        <p class="text-sm sm:text-base text-indigo-100">{{ $loket['deskripsi'] }}</p>
                                    </button>
                                @endforeach
                            </div>

                            @if($totalPages > 1)
                                <div class="flex flex-wrap items-center justify-center gap-2 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                                    <button wire:click="previousPage" @if($currentPage == 1) disabled @endif
                                            class="px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base font-semibold transition-colors {{ $currentPage == 1 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                                        <i class="fas fa-chevron-left mr-1"></i><span class="hidden sm:inline">Sebelumnya</span>
                                    </button>
                                    @for($i = 1; $i <= $totalPages; $i++)
                                        <button wire:click="goToPage({{ $i }})"
                                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg text-sm sm:text-base font-semibold transition-colors {{ $currentPage == $i ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                            {{ $i }}
                                        </button>
                                    @endfor
                                    <button wire:click="nextPage" @if($currentPage == $totalPages) disabled @endif
                                            class="px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base font-semibold transition-colors {{ $currentPage == $totalPages ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                                        <span class="hidden sm:inline">Selanjutnya</span><i class="fas fa-chevron-right ml-1"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Konten Berdasarkan Menu Aktif -->
                    @if($activeMenu === 'dashboard')
                        @include('components.petugas.dashboard')
                    @elseif($activeMenu === 'daftar-antrian')
                        @include('components.petugas.daftar-antrian')
                    @elseif($activeMenu === 'pemanggilan')
                        @include('components.petugas.pemanggilan')
                    @elseif($activeMenu === 'riwayat')
                        @include('components.petugas.riwayat')
                    @elseif($activeMenu === 'pengaturan')
                        @include('components.petugas.pengaturan')
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
