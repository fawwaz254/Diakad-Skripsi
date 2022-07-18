<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LogResetPassword
 */
class LogResetPassword extends Model
{
    protected $table = 'log_reset_password';

    protected $primaryKey = 'id_log_reset_password';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna',
        'created_by',
    ];

    protected $guarded = [];


    public function pengguna()
    {
        return $this->belongsTo('App\Models\Pengguna', 'id_pengguna');
    }

    public function cast_created_at()
    {
        return date_format(date_create($this->created_at), 'd M Y H:i');
    }
}
