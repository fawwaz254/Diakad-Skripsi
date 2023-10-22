<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class JawabanForm extends Model
{
    use SoftDeletes;

    protected $table = 'jawaban_form';

    protected $primaryKey = 'id_jawaban_form';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_form',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
