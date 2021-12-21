<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Setting;

class AddSettingBk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $data = new Setting;
        $data->key_setting = 'is_master_kesimpulan_bk';
        $data->value = 0;
        $data->keterangan = 'apabila bernilai 0 berarti kesimpulan diisi sendiri oleh bk , jika bernilai 1 maka diambil dari master';
        $data->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
