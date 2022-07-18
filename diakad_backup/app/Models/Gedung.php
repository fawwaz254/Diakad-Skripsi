<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Gedung
 */
class Gedung extends Model
{
    use SoftDeletes;

    protected $table = 'gedung';

    protected $primaryKey = 'id_gedung';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_jenis_gedung',
        'kode_gedung',
        'nm_gedung',
        'lokasi_gedung',
        'deskripsi_gedung',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}