<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PresensiMagang extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_magang';

    protected $primaryKey = 'id_presensi_magang';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'tanggal',
        'id_pembimbing_magang',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function pembimbingMagang()
    {
        return $this->belongsTo(PembimbingMagang::class, 'id_pembimbing_magang');
    }

    public function presensiMagangSiswa()
    {
        return $this->hasMany(PresensiMagangSiswa::class, 'id_presensi_magang');
    }
    public function convertDateFormat($label, $format)
    {
        $date = Carbon::parse($this->$label)->locale('id');
        $date->settings(['formatFunction' => 'translatedFormat']);
        return $date->format($format);
        // return date_format(date_create($this->$label), $format);
    }
}
