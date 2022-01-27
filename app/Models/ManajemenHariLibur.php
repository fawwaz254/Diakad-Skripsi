<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManajemenHariLibur extends Model
{
    use SoftDeletes;

    protected $table = 'manajemen_hari_libur';

    protected $primaryKey = 'manajemen_hari_libur_id';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'manajemen_hari_libur_id',
        'date',
        'explanation',
        'extra_money',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
