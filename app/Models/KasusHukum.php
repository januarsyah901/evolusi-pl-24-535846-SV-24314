<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasusHukum extends Model
{
    protected $table = 'kasus_hukums';

    protected $fillable = [
        'nomor_kasus',
        'judul',
        'kategori',
        'status',
        'keterangan',
    ];
}
