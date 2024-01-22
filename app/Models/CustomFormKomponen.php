<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomFormKomponen extends Model
{
    use SoftDeletes;

    protected $table = 'custom_form_komponen';

    protected $primaryKey = 'id_custom_form_komponen';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = [];
    protected $casts = [
        'komponen_settings' => 'array'
    ];

    protected $attributes = [
        'komponen_settings' => '{
            "mandatory": "true"
        }'
    ];

    public function form_respon()
    {
        return $this->hasMany(CustomFormRespon::class, 'id_custom_form_komponen');
    }
}
