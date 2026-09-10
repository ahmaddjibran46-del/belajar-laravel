<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'nama_menu',
        'harga_satuan',
        'qty',
        'catatan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'integer',
        'qty' => 'integer',
        'subtotal' => 'integer',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
