<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'table_peminjaman';

    protected $fillable = [
        'id_staff',
        'id_mahasiswa',
        'id_buku',
        'durasi_peminjaman',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status',
    ];



    public function staff()
    {
        return $this->belongsTo(User::class, 'id_staff', 'id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id');
    }
}
