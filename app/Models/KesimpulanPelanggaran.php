<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KesimpulanPelanggaran
 */
class KesimpulanPelanggaran extends Model
{
    use SoftDeletes;

    protected $table = 'kesimpulan_pelanggaran';

    protected $primaryKey = 'id_kesimpulan_pelanggaran';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_kesimpulan_pelanggaran',
        'poin_bawah_kesimpulan_pelanggaran',
        'poin_atas_kesimpulan_pelanggaran',
        'deskripsi_kesimpulan_pelanggaran_1',
        'deskripsi_kesimpulan_pelanggaran_2',
        'deskripsi_kesimpulan_pelanggaran_3',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}