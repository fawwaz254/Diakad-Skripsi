<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DetailJawabanForm extends Model
{
    use SoftDeletes;

    protected $table = 'detail_jawaban_form';

    protected $primaryKey = 'id_detail_jawaban_form';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_jawaban_form',
        'id_pertanyaan_form',
        'jawaban',
        'jawaban_lainnya',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function pertanyaan_form()
    {
        return $this->belongsTo(PertanyaanForm::class, 'id_pertanyaan_form');
    }
}
