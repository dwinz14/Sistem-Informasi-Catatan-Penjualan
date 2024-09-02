<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BarangRepositoryInterface;

class BarangController extends Controller
{
    private $barangRepository;

    public function __construct(BarangRepositoryInterface $barangRepository)
    {
        $this->barangRepository = $barangRepository;
    }

    public function index()
    {
        $barang = $this->barangRepository->getAll();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $jenisBarang = $this->barangRepository->getAllJenisBarang();
        return view('barang.create', compact('jenisBarang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_barang_id' => 'required|exists:jenis_barang,id',
            'stock_awal' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $this->barangRepository->create($data);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = $this->barangRepository->getById($id);
        // $barang = $this->barangRepository->findByEncryptedId($encryptedId);
        if (!$barang) {
            abort(404);
        }
        $jenisBarang = $this->barangRepository->getAllJenisBarang();
        return view('barang.edit', compact('barang', 'jenisBarang'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_barang_id' => 'required|exists:jenis_barang,id',
            'stock_awal' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $barang = $this->barangRepository->update($id, $data);
        if (!$barang) {
            abort(404);
        }
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = $this->barangRepository->delete($id);
        if (!$barang) {
            abort(404);
        }
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
