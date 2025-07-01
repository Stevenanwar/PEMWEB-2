<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = ['ruang_id', 'pegawai_id', 'tanggal', 'jam_mulai', 'jam_akhir', 'keterangan'];
}
