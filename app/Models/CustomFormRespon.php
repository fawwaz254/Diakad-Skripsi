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
}
