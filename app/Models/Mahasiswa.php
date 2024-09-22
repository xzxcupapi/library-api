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
}
