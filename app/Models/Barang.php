<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama', 'keterangan'];
    // protected $fillable = ['nama', 'jenis_barang_id', 'stock_awal', 'keterangan'];

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class);
    }

    // public function getEncryptedIdAttribute()
    // {
    //     return Crypt::encryptString($this->id);
    // }
}
