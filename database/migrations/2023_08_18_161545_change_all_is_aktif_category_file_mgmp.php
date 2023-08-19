<?php

use App\Models\CategoriFileMGMP;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAllIsAktifCategoryFileMgmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $category_file_mgmp = CategoriFileMGMP::whereNull('is_aktif')->get();
        if ($category_file_mgmp) {
            foreach ($category_file_mgmp as $c) {
                $c->is_aktif = 1;
                $c->save();
            }
        }
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
