<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    public function index()
    {
        $jenisBarang = JenisBarang::all();
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

        JenisBarang::create($data);
        return redirect()->route('jenis_barang.index')->with('success', 'Jenis barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jenisBarang = JenisBarang::findOrFail($id);
        return view('jenis_barang.edit', compact('jenisBarang'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->update($data);
        return redirect()->route('jenis_barang.index')->with('success', 'Jenis barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->delete();
        return redirect()->route('jenis_barang.index')->with('success', 'Jenis barang berhasil dihapus.');
    }
}