<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_mahasiswa' => 'required|exists:table_mahasiswa,id',
            'id_buku' => 'required|exists:table_buku,id',
            'durasi_peminjaman' => 'required|integer|min:3|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        $data['id_staff'] = Auth::id();
        $data['durasi_peminjaman'] = $data['durasi_peminjaman'] ?? 30;
        $data['tanggal_peminjaman'] = now();
        $data['tanggal_pengembalian'] = now()->addDays($data['durasi_peminjaman']);
        $data['status'] = 'peminjaman';

        $peminjaman = Peminjaman::create($data);

        return response()->json([
            'message' => 'Peminjaman berhasil ditambahkan',
            'data' => $peminjaman,
        ], 201);
    }

    public function getAll(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Anda perlu login'], 401);
        }

        $peminjaman = Peminjaman::with('staff', 'mahasiswa', 'buku')->get();
        return response()->json([
            'message' => 'Data Peminjaman',
            'data' => $peminjaman,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Anda perlu login'], 401);
        }

        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json(['message' => 'Peminjaman tidak ditemukan'], 404);
        }

        $peminjaman->status = 'selesai';
        $peminjaman->save();

        return response()->json([
            'message' => 'Peminjaman telah selesai',
        ], 204);
    }


    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json(['message' => 'Peminjaman tidak ditemukan'], 404);
        }

        $peminjaman->delete();

        return response()->json([
            'message' => 'Peminjaman berhasil dihapus',
        ], 200);
    }
}
