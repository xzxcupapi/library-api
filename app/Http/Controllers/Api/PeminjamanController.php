<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Buku;

class PeminjamanController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Incoming request data for store method:', $request->all());

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
        $data['tanggal_peminjaman'] = now();

        $durasiPeminjaman = (int) $data['durasi_peminjaman'];
        $data['tanggal_pengembalian'] = now()->addDays($durasiPeminjaman);

        $data['status'] = 'peminjaman';

        $peminjaman = Peminjaman::create($data);

        Buku::where('id', $request->id_buku)->update(['status' => 'dipinjam']);

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

    public function peminjamanDatatables(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Anda perlu login'], 401);
        }

        $searchValue = $request->input('search.value');
        $draw = $request->input('draw');
        $limit = $request->input('length');
        $offset = $request->input('start');

        $query = Peminjaman::with('mahasiswa', 'buku');

        if ($searchValue) {
            $query->whereHas('mahasiswa', function ($q) use ($searchValue) {
                $q->where('npm', 'LIKE', "%{$searchValue}%")
                    ->orWhere('nama_lengkap', 'LIKE', "%{$searchValue}%");
            })
                ->orWhereHas('buku', function ($q) use ($searchValue) {
                    $q->where('judul', 'LIKE', "%{$searchValue}%");
                });
        }

        $query->orderBy('status', 'asc');

        $totalFiltered = $query->count();

        $peminjaman = $query->offset($offset)->limit($limit)->get();

        $total = Peminjaman::count();

        $data = $peminjaman->map(function ($item) {
            return [
                'id' => $item->id,
                'npm' => $item->mahasiswa->npm,
                'nama_lengkap' => $item->mahasiswa->nama_lengkap,
                'judul_buku' => $item->buku->judul,
                'durasi_peminjaman' => $item->durasi_peminjaman,
                'tanggal_pengembalian' => $item->tanggal_pengembalian,
                'status' => $item->status,
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
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

        try {
            DB::beginTransaction();

            $peminjaman->status = 'selesai';
            $peminjaman->save();

            $buku = Buku::find($peminjaman->id_buku);
            if ($buku) {
                $buku->status = 'tersedia';
                $buku->save();
            } else {
                Log::warning("Buku dengan ID {$peminjaman->id_buku} tidak ditemukan saat menyelesaikan peminjaman.");
            }

            DB::commit();

            return response()->json([
                'message' => 'Peminjaman telah selesai dan buku telah dikembalikan',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui peminjaman: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui peminjaman'], 500);
        }
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
