<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PenerimaanJurusan
 */
class PenerimaanJurusan extends Model
{
    use SoftDeletes;

    protected $table = 'penerimaan_jurusan';

    protected $primaryKey = 'id_penerimaan_jurusan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_penerimaan',
        'id_jurusan',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}