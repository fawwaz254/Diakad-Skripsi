<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomFormRespon extends Model
{
    use SoftDeletes;

    protected $table = 'custom_form_respon';

    protected $primaryKey = 'id_custom_form_respon';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = [];

    public function form_sheet()
    {
        return $this->belongsTo(CustomFormSheet::class, 'id_custom_form_sheet');
    }

    public function form_komponen()
    {
        return $this->belongsTo(CustomFormKomponen::class, 'id_custom_form_komponen');
    }
}
