<?php

namespace App\Http\Controllers;

use App\Repositories\JenisBarangRepositoryInterface;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    private $jenisBarangRepository;

    public function __construct(JenisBarangRepositoryInterface $jenisBarangRepository)
    {
        $this->jenisBarangRepository = $jenisBarangRepository;
    }

    public function index()
    {
        $jenisBarang = $this->jenisBarangRepository->getAll();
        return view('jenis_barang.index', compact('jenisBarang'));
    }

    public function create()
    {
        return view('jenis_barang.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $this->jenisBarangRepository->create($data);
        return redirect()->route('jenis_barang.index')->with('success', 'Jenis barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jenisBarang = $this->jenisBarangRepository->getById($id);
        return view('jenis_barang.edit', compact('jenisBarang'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $this->jenisBarangRepository->update($id, $data);
        return redirect()->route('jenis_barang.index')->with('success', 'Jenis barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->jenisBarangRepository->delete($id);
        return redirect()->route('jenis_barang.index')->with('success', 'Jenis barang berhasil dihapus.');
    }
}