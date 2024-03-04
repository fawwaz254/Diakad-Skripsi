<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomForm extends Model
{
    use SoftDeletes;

    protected $table = 'custom_form';

    protected $primaryKey = 'id_custom_form';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = [];
    protected $casts = [
        'form_settings' => 'array'
    ];

    protected $attributes = [
        'form_settings' => '{
            "limit":"false",
            "editable":"true",
            "random":"false"
        }'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function form_komponen()
    {
        return $this->hasMany(CustomFormKomponen::class, 'id_custom_form');
    }
}
