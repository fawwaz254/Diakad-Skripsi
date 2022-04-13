<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class link_laporan_magang extends Model
{
    use SoftDeletes;

    protected $table = 'link_laporan_magang';

    protected $primaryKey = 'link_laporan_magang_id';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = [];
}
