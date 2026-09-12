<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pesanan',
        'midtrans_order_id',
        'meja_id',
        'nomor_meja_snapshot',
        'tipe',
        'metode_bayar',
        'nama_pelanggan',
        'catatan',
        'status',
        'total_harga',
        'kasir_id',
        'dibayar_pada',
    ];

    protected $casts = [
        'total_harga' => 'integer',
        'dibayar_pada' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Pesanan $pesanan) {
            if (empty($pesanan->kode_pesanan)) {
                $pesanan->kode_pesanan = static::generateKodePesanan();
            }
        });
    }

    public static function generateKodePesanan(): string
    {
        do {
            $kode = 'GC-' . Carbon::now()->format('ymd') . '-' . Str::upper(Str::random(4));
        } while (static::where('kode_pesanan', $kode)->exists());

        return $kode;
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

    public function items()
    {
        return $this->hasMany(PesananItem::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    // ----- Label & warna status, dipakai di tampilan -----

    public function labelStatus(): string
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'dibayar' => 'Sudah Dibayar',
            'diproses' => 'Sedang Diproses',
            'siap' => 'Siap Diantar/Diambil',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function warnaStatus(): string
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'bg-amber-100 text-amber-700',
            'dibayar' => 'bg-blue-100 text-blue-700',
            'diproses' => 'bg-indigo-100 text-indigo-700',
            'siap' => 'bg-emerald-100 text-emerald-700',
            'selesai' => 'bg-gray-200 text-gray-600',
            'dibatalkan' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function sudahDibayar(): bool
    {
        return !in_array($this->status, ['menunggu_pembayaran', 'dibatalkan']);
    }

    public function labelMetodeBayar(): string
    {
        return match ($this->metode_bayar) {
            'e_wallet' => 'E-Wallet / QRIS',
            'tunai_kasir' => 'Bayar di Kasir',
            default => '-',
        };
    }
}