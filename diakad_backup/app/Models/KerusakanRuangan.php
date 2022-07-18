<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KerusakanRuangan
 */
class KerusakanRuangan extends Model
{
    use SoftDeletes;

    protected $table = 'kerusakan_ruangan';

    protected $primaryKey = 'id_kerusakan_ruangan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'kode_kerusakan_ruangan',
        'nm_kerusakan_ruangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}