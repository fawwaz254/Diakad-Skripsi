<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RaporSisipanDeskripsi extends Model
{
    use SoftDeletes;
    protected $table = 'rapor_sisipan_deskripsi';

    protected $primaryKey = 'id_rapor_sisipan_deskripsi';

    public $timestamps = true;

    public $incrementing = false;
    protected $guarded = [];
}
