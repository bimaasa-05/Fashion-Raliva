<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $primaryKey = 'city_id';

    protected $fillable = [
        'nama_kota',
        'pulau',
    ];
}
