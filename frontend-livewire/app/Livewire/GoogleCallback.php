<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApiService;
use Illuminate\Http\Request;

class GoogleCallback extends Component
{
    public $error = null;
    public $loading = true;

    public function mount(Request $request, ApiService $apiService)
    {
        // Cek apakah ada token dari backend
        $token = $request->query('token');
        $error = $request->query('error');

        if ($error) {
            $this->error = $error;
            $this->loading = false;
            return;
        }

        if ($token) {
            // Simpan token ke session
            $apiService->setToken($token);
            
            // Redirect ke dashboard petugas
            return redirect('/petugas')->with('success', 'Login dengan Google berhasil!');
        }

        // Jika tidak ada token atau error, tampilkan error
        $this->error = 'Terjadi kesalahan saat login dengan Google';
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.google-callback')
            ->layout('components.layout');
    }
}
