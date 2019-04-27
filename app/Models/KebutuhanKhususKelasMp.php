<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KebutuhanKhususKelasMp
 */
class KebutuhanKhususKelasMp extends Model
{
    use SoftDeletes;

    protected $table = 'kebutuhan_khusus_kelas_mp';

    protected $primaryKey = 'id_kebutuhan_khusus_kelas_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas_mp',
        'id_kebutuhan_khusus',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}