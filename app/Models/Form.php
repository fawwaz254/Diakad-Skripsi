<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use SoftDeletes;

    protected $table = 'form';

    protected $primaryKey = 'id_form';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_role',
        'nm_form',
        'is_harian',
        'is_aktif',
        'start_time',
        'end_time',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function pertanyaan_form()
    {
        return $this->hasMany(PertanyaanForm::class, 'id_form');
    }

    public function jawaban_form()
    {
        return $this->hasMany(JawabanForm::class, 'id_form');
    }
}
