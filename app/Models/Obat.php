<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'nama_obat', 'kemasan', 'harga', 'stok',
    ];

    public function detailPeriksa(){
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }
}
