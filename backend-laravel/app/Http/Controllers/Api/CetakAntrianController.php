<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use Illuminate\Http\Request;

class CetakAntrianController extends Controller
{
    public function cetak($id)
    {
        $antrian = Antrian::with('loket')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'nomor_antrian' => $antrian->nomor_antrian,
                'nama_pasien' => $antrian->nama_pasien,
                'loket' => $antrian->loket->nama_loket,
                'tanggal' => $antrian->created_at->format('d/m/Y'),
                'waktu' => $antrian->created_at->format('H:i:s'),
                'keluhan' => $antrian->keluhan,
                'status' => $antrian->status
            ]
        ]);
    }
}
