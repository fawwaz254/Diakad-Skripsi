<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UrutanRaporSisipan extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'urutan_rapor_sisipan';

    protected $primaryKey = 'id_urutan_rapor_sisipan';

	public $timestamps = true;

    public $incrementing = false;
    protected $guarded = [];

}
