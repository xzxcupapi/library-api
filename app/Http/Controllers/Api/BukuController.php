<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


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
        $buku = Buku::orderByRaw("status = 'dipinjam' DESC")
            ->orderBy('judul', 'asc')
            ->get();

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

        // Logika pengurutan berdasarkan status
        $query->orderByRaw("CASE 
            WHEN status = 'dipinjam' THEN 1 
            WHEN status = 'tersedia' THEN 2 
            WHEN status = 'hilang' THEN 3 
            ELSE 4 
        END");

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
        Log::info('Request received for book search', [
            'judul' => $request->input('judul')
        ]);

        // Validasi input
        $request->validate([
            'judul' => 'required|string|min:2|max:100',
        ]);

        try {
            $judul = $request->input('judul');
            $keywords = preg_split('/\s+|[\r\n]+/', $judul);

            $query = Buku::query();
            foreach ($keywords as $keyword) {
                if (!empty($keyword)) {
                    $query->orWhere('judul', 'like', '%' . $keyword . '%');
                }
            }

            // Ambil hasil pencarian
            $buku = $query->get(['id', 'judul', 'pengarang', 'penerbit', 'tahun_terbit', 'status']);

            // Periksa jika tidak ada buku yang ditemukan
            if ($buku->isEmpty()) {
                Log::info('No matching books found', ['judul' => $judul]);
                return response()->json(['message' => 'Buku tidak ada'], 404);
            }

            // Jika ada buku ditemukan, batasi hingga 5 buku
            $bukuLimited = $buku->take(3);

            Log::info('Book search results', [
                'judul' => $judul,
                'results' => $bukuLimited
            ]);

            return response()->json([
                'message' => 'Data Buku',
                'data' => $bukuLimited,
            ], 200);
        } catch (\Exception $e) {
            // Mencatat error ke log
            Log::error('Error occurred during book search', [
                'error' => $e->getMessage(),
                'judul' => $request->input('judul'),
            ]);
            return response()->json(['message' => 'Tidak dapat mencari buku'], 500);
        }
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

    public function getBukuTersedia()
    {
        $buku = Buku::where('status', 'tersedia')->orderBy('judul', 'asc')->get();
        return response()->json(['data' => $buku], 200);
    }
}
