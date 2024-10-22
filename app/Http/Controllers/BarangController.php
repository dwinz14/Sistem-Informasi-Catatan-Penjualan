<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        // $jenisBarang = JenisBarang::all();
        // return view('barang.create', compact('jenisBarang'));
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            // 'jenis_barang_id' => 'required|exists:jenis_barang,id',
            // 'stock_awal' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        Barang::create($data);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        if (!$barang) {
            abort(404);
        }
        // $jenisBarang = JenisBarang::all();
        // return view('barang.edit', compact('barang', 'jenisBarang'));
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            // 'jenis_barang_id' => 'required|exists:jenis_barang,id',
            // 'stock_awal' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($id);
        if (!$barang) {
            abort(404);
        }
        $barang->update($data);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        if (!$barang) {
            abort(404);
        }
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
