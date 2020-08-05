<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KelasMpGrup
 */
class KelasMpGrup extends Model
{
    use SoftDeletes;

    protected $table = 'kelas_mp_grup';

    protected $primaryKey = 'id_kelas_mp_grup';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo('App\Models\Guru', 'id_guru');
    }

    public function kelas_mp()
    {
        return $this->hasMany('App\Models\KelasMp', 'id_kelas_mp_grup');
    }

    public function semester()
    {
        return $this->belongsTo('App\Models\Semester', 'id_semester');
    }
}
