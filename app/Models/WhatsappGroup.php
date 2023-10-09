<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsappGroup extends Model
{
    use SoftDeletes;

    protected $table = 'whatsapp_groups';

    protected $primaryKey = 'id_whatsapp_group';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelas', 'id_group', 'nm_group'
    ];

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas');
    }
}
