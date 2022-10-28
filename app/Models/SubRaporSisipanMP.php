<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SubRaporSisipanMP extends Model
{
    use SoftDeletes;

    protected $table = 'sub_rapor_sisipan_mp';

    protected $primaryKey = 'id_sub_rapor_sisipan_mp';

	public $timestamps = true;

    public $incrementing = false;
    protected $guarded = [];
}
