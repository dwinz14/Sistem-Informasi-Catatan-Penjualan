<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransaksiRepositoryInterface;
use App\Models\Barang;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    private $transaksiRepository;
    public function __construct(TransaksiRepositoryInterface $transaksiRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
    }
    public function index()
    {
        $transaksi = $this->transaksiRepository->getAll();
        return view('transaksi.index', compact('transaksi'));
    }
    public function create()
    {
        $barang = Barang::all();
        return view('transaksi.create', compact('barang'));
    }
    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'detail' => 'required|array',
            'detail.*.barang_id' => 'required|exists:barang,id',
            'detail.*.kuantitas' => 'required|integer|min:1',
            'detail.*.harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);
        // Hitung total harga
        $totalHarga = 0;
        foreach ($validatedData['detail'] as $detail) {
            $totalHarga += $detail['kuantitas'] * $detail['harga_jual'];
        }
        $validatedData['total_harga'] = $totalHarga;
        // Simpan transaksi
        $transaksi = $this->transaksiRepository->create($validatedData);
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }
    public function show($id)
    {
        $transaksi = $this->transaksiRepository->getById($id);
        if (!$transaksi) {
            abort(404);
        }
        return view('transaksi.show', compact('transaksi'));
    }
    public function edit($id)
    {
        $transaksi = $this->transaksiRepository->getById($id);
        if (!$transaksi) {
            abort(404);
        }
        $barang = Barang::all();
        return view('transaksi.edit', compact('transaksi', 'barang'));
    }
    public function update(Request $request, $id)
    {
        // Validasi data input (sesuaikan dengan kebutuhan)
        $validatedData = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'detail' => 'required|array',
            'detail.*.barang_id' => 'required|exists:barang,id',
            'detail.*.kuantitas' => 'required|integer|min:1',
            'detail.*.harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);
        // Hitung total harga
        $totalHarga = 0;
        foreach ($validatedData['detail'] as $detail) {
            $totalHarga += $detail['kuantitas'] * $detail['harga_jual'];
        }
        $validatedData['total_harga'] = $totalHarga;
        // Update transaksi
        $transaksi = $this->transaksiRepository->update($id, $validatedData);
        if (!$transaksi) {
            abort(404);
        }
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $transaksi = $this->transaksiRepository->delete($id);
        if (!$transaksi) {
            abort(404);
        }
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
