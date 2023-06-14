<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Featuremenu extends Model
{
    protected $table = 'feature_menu';

    protected $primaryKey = 'id_feature_menu';

	public $timestamps = false;

    public $incrementing = true;
    
    protected $fillable = [
        'id_modul',
        'is_aktif',
        'deskripsi',
    ];

    protected $guarded = [];
}
