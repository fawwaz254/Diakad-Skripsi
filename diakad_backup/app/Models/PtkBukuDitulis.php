<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkBukuDitulis
 */
class PtkBukuDitulis extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_buku_ditulis';

    protected $primaryKey = 'id_ptk_buku_ditulis';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'judul_buku',
        'tahun_buku',
        'penerbit_buku',
        'isbn_buku',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}