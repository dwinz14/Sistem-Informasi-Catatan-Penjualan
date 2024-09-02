<?php

namespace App\Repositories;

use App\Models\JenisBarang;

class JenisBarangRepository implements JenisBarangRepositoryInterface
{
    public function getAll()
    {
        return JenisBarang::all();
    }

    public function getById($id)
    {
        return JenisBarang::findOrFail($id);
    }

    public function create(array $data)
    {
        return JenisBarang::create($data);
    }

    public function update($id, array $data)
    {
        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->update($data);
        return $jenisBarang;
    }

    public function delete($id)
    {
        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->delete();
    }
}