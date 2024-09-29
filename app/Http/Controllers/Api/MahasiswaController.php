<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class MahasiswaController extends Controller
{
    // create
    public function store(Request $request)
    {
        Log::info('Data yang diterima:', $request->all());

        $validator = Validator::make($request->all(), [
            'npm' => 'nullable|string|unique:table_mahasiswa,npm|max:10',
            'nama_lengkap' => 'nullable|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'sidik_jari' => 'required|string|unique:table_mahasiswa,sidik_jari',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mahasiswa = Mahasiswa::create([
            'npm' => $request->npm ?? '-',
            'nama_lengkap' => $request->nama_lengkap ?? '-',
            'fakultas' => $request->fakultas ?? '-',
            'sidik_jari' => $request->sidik_jari,
        ]);

        Log::info('Data yang disimpan ke database:', $mahasiswa->toArray());

        return response()->json([
            'message' => 'Mahasiswa berhasil ditambahkan',
            'data' => $mahasiswa,
        ], 201);
    }

    // get all data
    public function getAllData(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Anda perlu login'], 401);
        }

        // Ambil parameter untuk DataTables
        $searchValue = $request->input('search.value');
        $draw = $request->input('draw');
        $limit = $request->input('length');
        $offset = $request->input('start');

        $query = Mahasiswa::query();

        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('npm', 'LIKE', "%{$searchValue}%")
                    ->orWhere('nama_lengkap', 'LIKE', "%{$searchValue}%")
                    ->orWhere('fakultas', 'LIKE', "%{$searchValue}%");
            });
        }

        $totalFiltered = $query->count();

        $mahasiswa = $query->offset($offset)->limit($limit)->get();

        $total = Mahasiswa::count();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $totalFiltered,
            'data' => $mahasiswa,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Log data yang diterima
        Log::info('Data yang diterima untuk update:', $request->all());

        $validator = Validator::make($request->all(), [
            'npm',
            'nama_lengkap' => 'string|max:255',
            'fakultas' => 'string|max:255',
            'sidik_jari',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mahasiswa = Mahasiswa::find($id);
        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Mahasiswa tidak ditemukan',
            ], 404);
        }

        if ($request->has('npm')) {
            $mahasiswa->npm = $request->npm;
        }
        if ($request->has('nama_lengkap')) {
            $mahasiswa->nama_lengkap = $request->nama_lengkap;
        }
        if ($request->has('fakultas')) {
            $mahasiswa->fakultas = $request->fakultas;
        }
        if ($request->has('sidik_jari')) {
            $mahasiswa->sidik_jari = $request->sidik_jari;
        }

        $mahasiswa->save();

        return response()->json([
            'message' => 'Mahasiswa berhasil diperbarui',
            'data' => $mahasiswa,
        ], 200);
    }


    // search by npm
    public function searchByNpm(Request $request)
    {
        $request->validate([
            'npm' => 'required|string|max:20',
        ]);

        $npm = $request->input('npm');
        $mahasiswa = Mahasiswa::where('npm', $npm)->first(['id', 'npm', 'nama_lengkap', 'fakultas']);

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa belum terdaftar'], 404);
        }

        return response()->json([
            'message' => 'Data Mahasiswa',
            'data' => $mahasiswa,
        ], 200);
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'message' => 'Mahasiswa tidak ditemukan',
            ], 404);
        }

        return response()->json($mahasiswa, 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Anda perlu login'], 401);
        }

        $mahasiswa = Mahasiswa::find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $mahasiswa->delete();
        return response()->json([
            'message' => 'Mahasiswa berhasil dihapus',
            'data' => $mahasiswa
        ], 200);
    }

    public function getMahasiswa()
    {
        $mahasiswa = Mahasiswa::orderBy('nama_lengkap', 'asc')->get();
        return response()->json(['data' => $mahasiswa], 200);
    }
}
