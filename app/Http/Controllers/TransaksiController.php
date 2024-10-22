<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        // Search functionality
        if ($request->has('search')) {
            $query->where('kode_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
        }

        // Sorting functionality
        $sortField = $request->get('sort_field', 'kode_transaksi');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $transaksi = $query->paginate(5);

        return view('transaksi.index', compact('transaksi'));
   }

    public function create()
    {
        $barang = Barang::all();
        return view('transaksi.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'detail' => 'required|array',
            'detail.*.barang_id' => 'required|exists:barang,id',
            'detail.*.kuantitas' => 'required|integer|min:1',
            'detail.*.harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Generate kode transaksi
        $tanggal = Carbon::parse($data['tanggal_transaksi'])->format('Ymd');
        $lastTransaction = Transaksi::whereDate('tanggal_transaksi', $data['tanggal_transaksi'])->orderBy('id', 'desc')->first();
        $nomorUrut = $lastTransaction ? (int)substr($lastTransaction->kode_transaksi, -4) + 1 : 1;
        $kodeTransaksi = 'TRX-' . $tanggal . '-' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        // Hitung total harga
        $totalHarga = 0;
        foreach ($data['detail'] as $detail) {
            $totalHarga += $detail['kuantitas'] * $detail['harga_jual'];
        }

        $transaksi = Transaksi::create([
            'kode_transaksi' => $kodeTransaksi,
            'tanggal_transaksi' => $data['tanggal_transaksi'],
            'total_harga' => $totalHarga,
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        // Simpan detail transaksi
        foreach ($data['detail'] as $detail) {
            $transaksi->detailTransaksi()->create([
                'barang_id' => $detail['barang_id'],
                'kuantitas' => $detail['kuantitas'],
                'harga_jual' => $detail['harga_jual'],
            ]);
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('detailTransaksi.barang')->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksi = Transaksi::with('detailTransaksi.barang')->findOrFail($id);
        $barang = Barang::all();
        return view('transaksi.edit', compact('transaksi', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'detail' => 'required|array',
            'detail.*.barang_id' => 'required|exists:barang,id',
            'detail.*.kuantitas' => 'required|integer|min:1',
            'detail.*.harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        // Hitung total harga
        $totalHarga = 0;
        foreach ($data['detail'] as $detail) {
            $totalHarga += $detail['kuantitas'] * $detail['harga_jual'];
        }

        $transaksi->update([
            'tanggal_transaksi' => $data['tanggal_transaksi'],
            'total_harga' => $totalHarga,
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        // Tambahkan untuk Update detail transaksi
        foreach ($data['detail'] as $detail) {
            $transaksi->detailTransaksi()->updateOrCreate(
                ['barang_id' => $detail['barang_id']],
                ['kuantitas' => $detail['kuantitas'], 'harga_jual' => $detail['harga_jual']]
            );
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
    
}
