<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ArsipDokuman
 */
class ArsipDokumenAkses extends Model
{
    use SoftDeletes;

    protected $table = 'arsip_dokumen_akses';

    protected $primaryKey = 'id_arsip_dokumen_akses';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_arsip_dokumen',
        'status_join_table',
        'id_unit_kerja',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}