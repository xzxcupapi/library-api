<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Validator;


class MahasiswaController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'npm' => 'required|string|unique:table_mahasiswa,npm|max:10',
            'nama_lengkap' => 'required|string|max:255',
            'fakultas' => 'required|string|max:255',
            'sidik_jari' => 'required|string|unique:table_mahasiswa,sidik_jari',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mahasiswa = Mahasiswa::create([
            'npm' => $request->npm,
            'nama_lengkap' => $request->nama_lengkap,
            'fakultas' => $request->fakultas,
            'sidik_jari' => $request->sidik_jari,
        ]);

        return response()->json([
            'message' => 'Mahasiswa created successfully',
            'data' => $mahasiswa,
        ], 201);
    }

    public function getAllData(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Anda perlu login'], 401);
        }

        $mahasiswa = Mahasiswa::all();
        return response()->json($mahasiswa);
    }

    public function searchByNpm(Request $request)
    {
        $request->validate([
            'npm' => 'required|string|max:20',
        ]);

        $npm = $request->input('npm');
        $mahasiswa = Mahasiswa::where('npm', $npm)->first(['npm', 'nama_lengkap', 'fakultas']);

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa belum terdaftar'], 404);
        }

        return response()->json($mahasiswa);
    }
}
