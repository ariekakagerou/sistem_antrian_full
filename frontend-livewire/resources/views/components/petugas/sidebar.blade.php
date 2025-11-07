<!-- Sidebar Navigasi Petugas -->
<!-- Mobile Overlay -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false" 
     class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"></div>

<!-- Sidebar -->
<div class="fixed left-0 top-0 h-full w-64 bg-gradient-to-b from-indigo-700 to-indigo-900 text-white shadow-2xl z-50 flex flex-col transform transition-transform duration-300 ease-in-out"
     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    <!-- Header Sidebar -->
    <div class="p-4 sm:p-6 border-b border-indigo-600">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                <i class="fas fa-hospital text-indigo-600 text-lg"></i>
            </div>
            <div>
                <h2 class="font-bold text-base sm:text-lg">Petugas Loket</h2>
                <p class="text-xs text-indigo-200">RS Sehat Selalu</p>
            </div>
            </div>
            <!-- Close Button Mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-indigo-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Info Waktu & Shift -->
        <div class="mt-3 sm:mt-4 text-xs bg-indigo-800 bg-opacity-50 rounded-lg p-2 sm:p-3">
            <div class="flex items-center justify-between">
                <span class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <span id="current-time">{{ now()->format('H:i') }}</span>
                </span>
                <span class="px-2 py-1 bg-indigo-600 rounded text-xs">
                    {{ now()->format('H') < 14 ? 'Shift Pagi' : 'Shift Sore' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex-1 overflow-y-auto py-3 sm:py-4">
        <ul class="space-y-1 px-2 sm:px-3">
            <li>
                <button wire:click="changeMenu('dashboard')" 
                   @click="sidebarOpen = false"
                   class="w-full flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg transition-all duration-200 text-sm sm:text-base
                          {{ $activeMenu === 'dashboard' ? 'bg-white text-indigo-700 shadow-lg' : 'text-indigo-100 hover:bg-indigo-800' }}">
                    <i class="fas fa-home w-4 sm:w-5"></i>
                    <span class="font-semibold">Dashboard</span>
                </button>
            </li>
            <li>
                <button wire:click="changeMenu('daftar-antrian')" 
                   @click="sidebarOpen = false"
                   class="w-full flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg transition-all duration-200 text-sm sm:text-base
                          {{ $activeMenu === 'daftar-antrian' ? 'bg-white text-indigo-700 shadow-lg' : 'text-indigo-100 hover:bg-indigo-800' }}">
                    <i class="fas fa-users w-4 sm:w-5"></i>
                    <span class="font-semibold">Daftar Antrian</span>
                    @if(isset($jumlahMenunggu) && $jumlahMenunggu > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full">
                            {{ $jumlahMenunggu }}
                        </span>
                    @endif
                </button>
            </li>
            <li>
                <button wire:click="changeMenu('pemanggilan')" 
                   @click="sidebarOpen = false"
                   class="w-full flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg transition-all duration-200 text-sm sm:text-base
                          {{ $activeMenu === 'pemanggilan' ? 'bg-white text-indigo-700 shadow-lg' : 'text-indigo-100 hover:bg-indigo-800' }}">
                    <i class="fas fa-bullhorn w-4 sm:w-5"></i>
                    <span class="font-semibold">Pemanggilan Pasien</span>
                </button>
            </li>
            <li>
                <button wire:click="changeMenu('riwayat')" 
                   @click="sidebarOpen = false"
                   class="w-full flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg transition-all duration-200 text-sm sm:text-base
                          {{ $activeMenu === 'riwayat' ? 'bg-white text-indigo-700 shadow-lg' : 'text-indigo-100 hover:bg-indigo-800' }}">
                    <i class="fas fa-history w-4 sm:w-5"></i>
                    <span class="font-semibold">Riwayat Pelayanan</span>
                </button>
            </li>
            <li>
                <button wire:click="changeMenu('pengaturan')" 
                   @click="sidebarOpen = false"
                   class="w-full flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg transition-all duration-200 text-sm sm:text-base
                          {{ $activeMenu === 'pengaturan' ? 'bg-white text-indigo-700 shadow-lg' : 'text-indigo-100 hover:bg-indigo-800' }}">
                    <i class="fas fa-cog w-4 sm:w-5"></i>
                    <span class="font-semibold">Pengaturan Akun</span>
                </button>
            </li>
        </ul>
    </nav>

    <!-- Footer Sidebar -->
    <div class="p-3 sm:p-4 border-t border-indigo-600">
        <!-- User Info -->
        <div class="flex items-center space-x-2 sm:space-x-3 mb-3 px-1 sm:px-2">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-white"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-xs sm:text-sm truncate">{{ $petugasNama ?? 'Petugas' }}</p>
                <p class="text-xs text-indigo-200 truncate">{{ $loketNama ?? 'Belum pilih loket' }}</p>
            </div>
        </div>
        
        <!-- Logout Button -->
        <button wire:click="logout" 
                class="w-full flex items-center justify-center space-x-2 px-3 sm:px-4 py-2 sm:py-3 bg-red-500 hover:bg-red-600 rounded-lg transition-all duration-200 text-sm sm:text-base font-semibold">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </button>
    </div>
</div>

<!-- Update waktu real-time -->
<script>
    setInterval(function() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = hours + ':' + minutes;
        }
    }, 1000);
    
    // Debug: Log ketika menu diklik
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Sidebar loaded');
    });
</script>
