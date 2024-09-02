<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    use HasFactory;

    protected $table = 'jenis_barang';

    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    // Jika Anda ingin menambahkan aturan validasi di level model
    public static $rules = [
        'nama' => 'required|string|max:255',
        'deskripsi' => 'nullable|string'
    ];

    // Anda bisa menambahkan relasi jika diperlukan
    // Contoh:
    // public function barang()
    // {
    //     return $this->hasMany(Barang::class);
    // }

    // Anda juga bisa menambahkan metode-metode khusus jika diperlukan
    // Contoh:
    // public function getNamaKapital()
    // {
    //     return strtoupper($this->nama);
    // }
}