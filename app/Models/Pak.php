<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Pak
 */
class Pak extends Model
{
    use SoftDeletes;

    protected $table = 'pak';

    protected $primaryKey = 'id_pak';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester_mulai',
        'id_semester_selesai',
        'id_subkategori_rapb',
        'id_unit_kerja',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}