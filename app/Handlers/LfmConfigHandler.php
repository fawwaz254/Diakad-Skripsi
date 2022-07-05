<?php

namespace App\Handlers;

class LfmConfigHandler extends \UniSharp\LaravelFilemanager\Handlers\ConfigHandler
{
    public function userField()
    {
        $prefix = auth_data()->sekolah_data->nm_singkat_sekolah;
        $user_id = auth_data()->pengguna->id_pengguna;
        return $prefix.'/editor/'.$user_id;
    }
}
