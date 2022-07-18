<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ArsipDokumenFile
 */
class ArsipDokumenFile extends Model
{
    use SoftDeletes;

    protected $table = 'arsip_dokumen_file';

    protected $primaryKey = 'id_arsip_dokumen_file';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_arsip_dokumen',
        'nm_arsip_dokumen_file',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}