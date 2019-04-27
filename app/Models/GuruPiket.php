<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GuruPiket
 */
class GuruPiket extends Model
{
    use SoftDeletes;

    protected $table = 'guru_piket';

    protected $primaryKey = 'id_guru_piket';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_guru',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}