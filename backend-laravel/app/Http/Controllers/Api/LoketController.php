<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoketController extends Controller
{
    /**
     * Menampilkan semua loket yang tersedia
     * GET /api/loket
     */
    public function index()
    {
        $lokets = Loket::all();
        return response()->json([
            'success' => true,
            'data' => $lokets
        ]);
    }

    /**
     * Menyimpan loket baru
     * POST /api/loket
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_loket' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $loket = Loket::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Loket berhasil ditambahkan',
                'data' => $loket
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan loket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail loket
     * GET /api/loket/{id}
     */
    public function show($id)
    {
        $loket = Loket::find($id);

        if (!$loket) {
            return response()->json([
                'success' => false,
                'message' => 'Loket tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $loket
        ]);
    }

    /**
     * Mengupdate data loket
     * PUT /api/loket/{id}
     */
    public function update(Request $request, $id)
    {
        $loket = Loket::find($id);

        if (!$loket) {
            return response()->json([
                'success' => false,
                'message' => 'Loket tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_loket' => 'sometimes|required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $loket->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Loket berhasil diupdate',
                'data' => $loket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate loket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus loket
     * DELETE /api/loket/{id}
     */
    public function destroy($id)
    {
        $loket = Loket::find($id);

        if (!$loket) {
            return response()->json([
                'success' => false,
                'message' => 'Loket tidak ditemukan'
            ], 404);
        }

        try {
            $loket->delete();
            return response()->json([
                'success' => true,
                'message' => 'Loket berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus loket',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
