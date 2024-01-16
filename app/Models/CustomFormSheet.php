<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomFormSheet extends Model
{
    use SoftDeletes;

    protected $table = 'custom_form_sheet';

    protected $primaryKey = 'id_custom_form_sheet';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = [];

    public function form()
    {
        return $this->belongsTo(CustomForm::class, 'id_custom_form');
    }

    public function form_respon()
    {
        return $this->hasMany(CustomFormRespon::class,'id_custom_form_sheet');
    }
}
