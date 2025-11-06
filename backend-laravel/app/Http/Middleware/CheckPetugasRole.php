<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPetugasRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        if (auth()->user()->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk fitur ini.'
            ], 403);
        }

        return $next($request);
    }
}
