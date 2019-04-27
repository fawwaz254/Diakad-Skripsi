<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KondisiRuangan
 */
class KondisiRuangan extends Model
{
    use SoftDeletes;

    protected $table = 'kondisi_ruangan';

    protected $primaryKey = 'id_kondisi_ruangan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ruangan',
        'id_kerusakan_ruangan',
        'persentase_kerusakan_ruangan',
        'keterangan_kerusakan_ruangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}