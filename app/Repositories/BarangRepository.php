<?php

namespace App\Repositories;

use App\Models\Barang;
use App\Models\JenisBarang;
// use Illuminate\Support\Facades\Crypt;

class BarangRepository implements BarangRepositoryInterface
{
    public function getAll()
    {
        return Barang::with('jenisBarang')->get();
    }

    // public function findByEncryptedId($encryptedId)
    // {
    //     try {
    //         $id = Crypt::decryptString($encryptedId);
    //         return Barang::find($id);
    //     } catch (\Exception $e) {
    //         return null;
    //     }
    // }
    public function getById($id)
    {
        return Barang::findOrFail($id);
    }
    public function create(array $data)
    {
        return Barang::create($data);
    }

    public function update($id, array $data)
    {
        $barang = Barang::findOrFail($id);
        // $barang = $this->findByEncryptedId($encryptedId);
        if ($barang) {
            $barang->update($data);
        }
        return $barang;
    }

    public function delete($id)
    {
        $barang = Barang::findOrFail($id);
        // $barang = $this->findByEncryptedId($encryptedId);
        if ($barang) {
            $barang->delete();
        }
        return $barang;
    }

    public function getAllJenisBarang()
    {
        return JenisBarang::all();
    }
}