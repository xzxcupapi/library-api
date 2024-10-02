<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'table_mahasiswa';

    protected $fillable = [
        'npm',
        'nama_lengkap',
        'fakultas',
        'sidik_jari',
    ];

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class, 'id_mahasiswa', 'id');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_mahasiswa', 'id');
    }
}
