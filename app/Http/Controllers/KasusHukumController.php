<?php

namespace App\Http\Controllers;

use App\Models\KasusHukum;
use Illuminate\Http\Request;

class KasusHukumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kasus = KasusHukum::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $kasus,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_kasus' => 'required|string|unique:kasus_hukums,nomor_kasus',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'status' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $kasus = KasusHukum::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kasus hukum berhasil ditambahkan',
            'data' => $kasus,
        ], 201);
    }

    public function show(KasusHukum $kasusHukum)
    {
        return response()->json([
            'success' => true,
            'data' => $kasusHukum,
        ], 200);
    }

    public function update(Request $request, KasusHukum $kasusHukum)
    {
        $validated = $request->validate([
            'nomor_kasus' => 'sometimes|string|unique:kasus_hukums,nomor_kasus,' . $kasusHukum->id,
            'judul' => 'sometimes|string|max:255',
            'kategori' => 'sometimes|string|max:100',
            'status' => 'sometimes|string',
            'keterangan' => 'nullable|string',
        ]);

        $kasusHukum->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kasus hukum berhasil diperbarui',
            'data' => $kasusHukum,
        ], 200);
    }

    public function destroy(KasusHukum $kasusHukum)
    {
        $kasusHukum->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kasus hukum berhasil dihapus',
        ], 200);
    }
}
