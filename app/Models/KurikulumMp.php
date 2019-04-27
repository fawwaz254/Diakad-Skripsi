<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KurikulumMp
 */
class KurikulumMp extends Model
{
    use SoftDeletes;

    protected $table = 'kurikulum_mp';

    protected $primaryKey = 'id_kurikulum_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kurikulum',
        'id_mata_pelajaran',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}