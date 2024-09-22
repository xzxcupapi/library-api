<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sidik_jari' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid input data'], 400);
        }

        $mahasiswa = Mahasiswa::where('sidik_jari', $request->sidik_jari)->first();
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $today = Carbon::today();
        $existingKunjungan = Kunjungan::where('id_mahasiswa', $mahasiswa->id)
            ->whereDate('tanggal_kunjungan', $today)
            ->first();
        if ($existingKunjungan) {
            return response()->json(['message' => 'Mahasiswa sudah melakukan kunjungan hari ini'], 400);
        }

        $kunjungan = Kunjungan::create([
            'id_mahasiswa' => $mahasiswa->id,
            'tanggal_kunjungan' => now(),
        ]);

        return response()->json([
            'message' => 'Kunjungan berhasil dicatat',
            'kunjungan' => $kunjungan,
        ], 201);
    }

    public function getAll(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Login terlebih dahulu'], 401);
        }

        $kunjungan = Kunjungan::with('mahasiswa')->get();

        return response()->json([
            'message' => 'Data Kunjungan',
            'kunjungan' => $kunjungan,
        ], 200);
    }
}
