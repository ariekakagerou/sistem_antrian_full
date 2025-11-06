<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100 flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        @if($loading)
            <!-- Loading State -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-100 rounded-full mb-4 animate-pulse">
                    <i class="fas fa-spinner fa-spin text-3xl text-indigo-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Memproses Login...</h2>
                <p class="text-gray-600">Mohon tunggu sebentar</p>
            </div>
        @else
            <!-- Error State -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Login Gagal</h2>
                <p class="text-gray-600 mb-6">{{ $error }}</p>
                
                <a href="/petugas" 
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Login
                </a>
            </div>
        @endif
    </div>
</div>
