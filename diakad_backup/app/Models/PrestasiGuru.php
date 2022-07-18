<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PrestasiSiswa
 */
class PrestasiGuru extends Model
{
    use SoftDeletes;

    protected $table = 'prestasi_guru';

    protected $primaryKey = 'id_prestasi_guru';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];






}