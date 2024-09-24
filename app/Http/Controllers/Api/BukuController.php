<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input, status tidak lagi divalidasi dari frontend
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1000|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $buku = Buku::create([
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'status' => 'tersedia',
        ]);

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku,
        ], 201);
    }

    public function getBuku(Request $request)
    {
        $buku = Buku::all();
        return response()->json([
            'message' => 'Data Buku',
            'data' => $buku,
        ], 200);
    }


    public function getAll(Request $request)
    {
        $searchValue = $request->input('search.value');
        $draw = $request->input('draw');
        $limit = $request->input('length');
        $offset = $request->input('start');

        $query = Buku::query();
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('judul', 'LIKE', "%{$searchValue}%")
                    ->orWhere('pengarang', 'LIKE', "%{$searchValue}%")
                    ->orWhere('status', 'LIKE', "%{$searchValue}%");
            });
        }

        $totalFiltered = $query->count();
        $buku = $query->offset($offset)->limit($limit)->get();

        $total = Buku::count();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $totalFiltered,
            'data' => $buku,
        ], 200);
    }



    public function show($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan',
            ], 404);
        }

        return response()->json($buku, 200);
    }

    public function searchByJudul(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|min:2|max:100',
        ]);

        $judul = $request->input('judul');

        $prefix = substr($judul, 0, 2);

        $buku = Buku::where('judul', 'like', $prefix . '%')->get(['id', 'judul', 'pengarang', 'penerbit', 'tahun_terbit', 'status']);

        if ($buku->isEmpty()) {
            return response()->json(['message' => 'Buku tidak ada'], 404);
        }

        return response()->json([
            'message' => 'Data Buku',
            'data' => $buku,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'nullable|string|max:255',
            'pengarang' => 'nullable|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'status' => 'nullable|in:tersedia,dipinjam,hilang',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $buku->update($request->only(['judul', 'pengarang', 'penerbit', 'tahun_terbit', 'status']));

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku,
        ], 200);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }
}
