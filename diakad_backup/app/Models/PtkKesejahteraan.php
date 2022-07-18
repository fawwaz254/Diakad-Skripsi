<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkKesejahteraan
 */
class PtkKesejahteraan extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_kesejahteraan';

    protected $primaryKey = 'id_ptk_kesejahteraan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'jenis_kesejahteraan',
        'nm_kesejahteraan',
        'penyelenggara_kesejahteraan',
        'tahun_mulai_kesejahteraan',
        'tahun_selesai_kesejahteraan',
        'status_kesejahteraan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}