<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileSekolah extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_file_sekolah';

	public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_file_sekolah',
        'nama_file',
        'link_gdrive',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'

    ];
}
