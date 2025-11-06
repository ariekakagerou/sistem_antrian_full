<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect ke halaman login Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    /**
     * Callback dari Google setelah login
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            // Redirect ke frontend dengan error
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:8001');
            return redirect($frontendUrl . '/auth/google/callback?error=' . urlencode('Gagal login dengan Google: ' . $e->getMessage()));
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (!$user) {
            // Buat user baru jika belum terdaftar
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
                'role' => 'petugas', // Default role untuk user dari Google
            ]);
        } else {
            // Update google_id jika user sudah ada tapi belum memiliki google_id
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }
        }

        // Generate token untuk API
        $token = $user->createToken('google-token')->plainTextToken;

        // Redirect ke frontend dengan token
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:8001');
        return redirect($frontendUrl . '/auth/google/callback?token=' . $token);
    }

    // Method untuk menampilkan halaman lengkapi pendaftaran (jika diperlukan)
    public function showGoogleProfileComplete()
    {
        $googleRegister = session('google_register');
        if (!$googleRegister) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak valid, silakan coba login kembali.'
            ], 401);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Lengkapi data pendaftaran',
            'data' => [
                'email' => $googleRegister['email'],
                'name' => $googleRegister['fullname'] ?? '',
            ]
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        // Hapus token saat ini
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout'
        ]);
    }
}
