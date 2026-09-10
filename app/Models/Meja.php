<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meja extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_meja',
        'kode_qr',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Meja $meja) {
            if (empty($meja->kode_qr)) {
                $meja->kode_qr = Str::upper(Str::random(8));
            }
        });
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }

    /**
     * URL lengkap yang dituju saat QR code meja ini di-scan pelanggan.
     */
    public function getUrlPesanAttribute(): string
    {
        return route('menu.index', $this->kode_qr);
    }
}
