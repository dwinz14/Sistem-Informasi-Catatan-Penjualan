<?php
namespace App\Repositories;
use App\Models\Transaksi;
use Carbon\Carbon;
class TransaksiRepository implements TransaksiRepositoryInterface
{
    public function getAll()
    {
        return Transaksi::with('detailTransaksi.barang')->orderBy('tanggal_transaksi', 'desc')->get();
    }
    public function getById($id)
    {
        return Transaksi::with('detailTransaksi.barang')->find($id);
    }
    public function create(array $data)
    {
        // Generate kode transaksi
        $tanggal = Carbon::parse($data['tanggal_transaksi'])->format('Ymd');
        $lastTransaction = Transaksi::whereDate('tanggal_transaksi', $data['tanggal_transaksi'])->orderBy('id', 'desc')->first();
        $nomorUrut = $lastTransaction ? (int)substr($lastTransaction->kode_transaksi, -4) + 1 : 1;
        $kodeTransaksi = 'TRX-' . $tanggal . '-' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
        $transaksi = Transaksi::create([
            'kode_transaksi' => $kodeTransaksi,
            'tanggal_transaksi' => $data['tanggal_transaksi'],
            'total_harga' => $data['total_harga'],
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
        return $transaksi;
    }
    public function update($id, array $data)
    {
        $transaksi = $this->getById($id);
        if (!$transaksi) {
            return null;
        }
        $transaksi->update([
            'tanggal_transaksi' => $data['tanggal_transaksi'],
            'total_harga' => $data['total_harga'],
            'keterangan' => $data['keterangan'] ?? null,
        ]);
        // Update detail transaksi (opsional, tergantung kebutuhan)
        // ...
        return $transaksi;
    }
    public function delete($id)
    {
        $transaksi = $this->getById($id);
        if (!$transaksi) {
            return null;
        }
        $transaksi->delete();
        return $transaksi;
    }
}