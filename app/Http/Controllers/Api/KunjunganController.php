<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KunjunganController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sidik_jari' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Input data tidak valid', ['errors' => $validator->errors()]);
            return response()->json(['message' => 'Invalid input data'], 400);
        }

        $mahasiswa = Mahasiswa::where('sidik_jari', $request->sidik_jari)->first();
        if (!$mahasiswa) {
            Log::error('Mahasiswa tidak ditemukan', ['sidik_jari' => $request->sidik_jari]);
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $today = Carbon::today();
        $existingKunjungan = Kunjungan::where('id_mahasiswa', $mahasiswa->id)
            ->whereDate('tanggal_kunjungan', $today)
            ->first();
        if ($existingKunjungan) {
            Log::info('Mahasiswa sudah melakukan kunjungan hari ini', ['mahasiswa_id' => $mahasiswa->id]);
            return response()->json(['message' => 'Mahasiswa sudah melakukan kunjungan hari ini'], 400);
        }

        $kunjungan = Kunjungan::create([
            'id_mahasiswa' => $mahasiswa->id,
            'tanggal_kunjungan' => now(),
        ]);

        Log::info('Kunjungan berhasil dicatat', [
            'mahasiswa_id' => $mahasiswa->id,
            'kunjungan' => $kunjungan,
        ]);

        return response()->json([
            'message' => 'Kunjungan berhasil dicatat',
            'kunjungan' => $kunjungan,
        ], 201);
    }


    public function getKunjungan(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Login terlebih dahulu'], 401);
        }

        $kunjungan = Kunjungan::with('mahasiswa')
            ->orderBy('tanggal_kunjungan', 'desc')
            ->get();

        $dataKunjungan = $kunjungan->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_mahasiswa' => $item->mahasiswa ? $item->mahasiswa->nama_lengkap : null,
                'tanggal_kunjungan' => Carbon::parse($item->tanggal_kunjungan)->format('d-m-Y'),
            ];
        });

        // Kembalikan data kunjungan dalam format JSON
        return response()->json($dataKunjungan, 200);
    }


    public function getAll(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Login terlebih dahulu'], 401);
        }

        $searchValue = $request->input('search.value');
        $draw = $request->input('draw');
        $limit = $request->input('length');
        $offset = $request->input('start');
        $month = $request->input('month');

        $query = Kunjungan::with('mahasiswa');

        if ($searchValue) {
            $query->whereHas('mahasiswa', function ($q) use ($searchValue) {
                $q->where('nama_lengkap', 'LIKE', "%{$searchValue}%");
            });
        }

        if ($month) {
            $query->whereMonth('tanggal_kunjungan', $month);
        }

        $query->orderBy('tanggal_kunjungan', 'desc');

        $totalFiltered = $query->count();

        $kunjungan = $query->offset($offset)->limit($limit)->get();

        $total = Kunjungan::count();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $totalFiltered,
            'data' => $kunjungan,
        ], 200);
    }
}
