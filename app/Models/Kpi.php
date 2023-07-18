<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $table = 'kpi';

    protected $primaryKey = 'id_kpi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester',
        'id_siswa',
        'id_kelas',
    ];

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function haskpi()
    {
        return $this->hasMany(HasKpi::class, 'id_kpi');
    }

    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'id_kelas');
    }
}
