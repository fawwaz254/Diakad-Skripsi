<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PertanyaanForm extends Model
{
    use SoftDeletes;

    protected $table = 'pertanyaan_form';

    protected $primaryKey = 'id_pertanyaan_form';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_form',
        'jenis_pertanyaan',
        'nm_pertanyaan_form',
        'urutan',
        'options',
        'others',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
